/**
 * Shopify-style variant builder — Combination pricing.
 *
 * Reads data-attributes and data-existing from #vb-container.
 * Each combination (one value per attribute type) gets its own price + stock row.
 *
 * Form output per combination index i:
 *   combos[i][values][]       — hidden, one per value_id in the combo
 *   combos[i][price]          — decimal price (0 = use product base price)
 *   combos[i][stock]          — integer stock (-1 = no inventory control)
 *   combos[i][combination_id] — existing PK, or empty string for new
 *
 * data-existing format (from PHP):
 *   [{"combination_id":5,"value_ids":[12,34],"price":5000,"stock":10}, …]
 *
 * Inline creation:
 *   "Nuevo valor" input → POST /attribute-value/inline-store
 *   "Crear tipo"  link  → POST /attribute/inline-store
 */
(function () {
  'use strict';

  var wrap = document.getElementById('vb-container');
  if (!wrap) return;

  var allAttrs  = JSON.parse(wrap.dataset.attributes || '[]');
  var existing  = JSON.parse(wrap.dataset.existing   || '[]');
  var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

  // Map<valueId:number, {attrId, attrName, valName}>
  var selected    = new Map();
  // Maps keyed by comboKey (sorted value IDs joined by "_")
  var comboPrices = new Map();
  var comboStocks = new Map();
  var comboIds    = new Map();

  var activeAttrId = null;

  /* ── Helpers ──────────────────────────────────────────────── */
  function comboKey(valueIds) {
    return valueIds.slice().sort(function (a, b) { return a - b; }).join('_');
  }

  function cartesian(groups) {
    if (!groups.length) return [[]];
    var rest   = cartesian(groups.slice(1));
    var result = [];
    groups[0].forEach(function (item) {
      rest.forEach(function (combo) { result.push([item].concat(combo)); });
    });
    return result;
  }

  /* ── Init ─────────────────────────────────────────────────── */
  function init() {
    existing.forEach(function (combo) {
      var ids = (combo.value_ids || []).map(Number);
      ids.forEach(function (vid) {
        var attr = allAttrs.find(function (a) {
          return a.values.some(function (v) { return v.id === vid; });
        });
        if (!attr) return;
        var val = attr.values.find(function (v) { return v.id === vid; });
        if (!val) return;
        selected.set(vid, { attrId: attr.id, attrName: attr.name, valName: val.value });
      });
      var key = comboKey(ids);
      comboPrices.set(key, combo.price != null ? parseFloat(combo.price) : 0);
      comboStocks.set(key, combo.stock != null ? parseInt(combo.stock)   : 0);
      comboIds.set(key, combo.combination_id || null);
    });
    renderPills();
    renderTable();
    updateBadges();
  }

  /* ── Attribute pills ──────────────────────────────────────── */
  function renderPills() {
    var el = document.getElementById('vb-attrs');
    if (!el) return;
    el.innerHTML = allAttrs.map(function (a) {
      return '<button type="button" class="vb-attr-pill" data-attr-id="' + a.id + '">' +
        a.name +
        '<span class="vb-badge" id="vb-badge-' + a.id + '" style="display:none"></span>' +
        '</button>';
    }).join('');
    el.querySelectorAll('.vb-attr-pill').forEach(function (btn) {
      btn.addEventListener('click', function () {
        showPicker(parseInt(btn.dataset.attrId));
      });
    });
  }

  /* ── Value picker ─────────────────────────────────────────── */
  function showPicker(attrId) {
    activeAttrId = attrId;
    var attr = allAttrs.find(function (a) { return a.id === attrId; });
    if (!attr) return;

    document.querySelectorAll('.vb-attr-pill').forEach(function (b) {
      b.classList.toggle('active', parseInt(b.dataset.attrId) === attrId);
    });

    var titleEl = document.getElementById('vb-picker-title');
    if (titleEl) titleEl.textContent = attr.name;

    var grid = document.getElementById('vb-values-grid');
    if (!grid) return;
    renderChips(attr, grid);

    var picker = document.getElementById('vb-picker');
    if (picker) picker.classList.remove('d-none');

    var selAllBtn = document.getElementById('vb-select-all');
    if (selAllBtn) {
      selAllBtn.onclick = function () {
        var g = document.getElementById('vb-values-grid');
        if (!g) return;
        g.querySelectorAll('.vb-chip').forEach(function (chip) {
          if (!chip.classList.contains('selected')) toggleValue(chip, attr, true);
        });
      };
    }
  }

  function renderChips(attr, grid) {
    grid.innerHTML = attr.values.map(function (v) {
      var sel = selected.has(parseInt(v.id));
      return '<span class="vb-chip' + (sel ? ' selected' : '') + '" ' +
        'data-attr-id="'   + attr.id   + '" ' +
        'data-attr-name="' + attr.name + '" ' +
        'data-val-id="'    + v.id      + '" ' +
        'data-val-name="'  + v.value   + '">' +
        v.value + '</span>';
    }).join('');

    grid.querySelectorAll('.vb-chip').forEach(function (chip) {
      chip.addEventListener('click', function () { toggleValue(chip, attr); });
    });
  }

  /* ── Toggle a single value chip ───────────────────────────── */
  function toggleValue(chip, attr, forceOn) {
    var valId  = parseInt(chip.dataset.valId);
    var remove = forceOn === undefined ? selected.has(valId) : !forceOn;

    if (remove) {
      selected.delete(valId);
      chip.classList.remove('selected');
    } else {
      selected.set(valId, {
        attrId  : parseInt(chip.dataset.attrId),
        attrName: chip.dataset.attrName,
        valName : chip.dataset.valName,
      });
      chip.classList.add('selected');
    }
    renderTable();
    updateBadges();
  }

  /* ── Inline: add a new VALUE to the active attribute ─────── */
  var newValInput = document.getElementById('vb-new-val-input');
  var newValBtn   = document.getElementById('vb-new-val-btn');
  if (newValBtn && newValInput) {
    newValBtn.addEventListener('click', function () {
      var val = newValInput.value.trim();
      if (!val || activeAttrId === null) return;
      newValBtn.disabled = true;
      fetch('/attribute-value/inline-store', {
        method : 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN' : csrfToken,
          'Accept'       : 'application/json',
        },
        body: JSON.stringify({ attr_id: activeAttrId, value: val }),
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.error) { alert(data.error); return; }
          var attr = allAttrs.find(function (a) { return a.id === activeAttrId; });
          if (attr) attr.values.push({ id: data.id, value: data.value });
          var grid = document.getElementById('vb-values-grid');
          if (attr && grid) {
            renderChips(attr, grid);
            var newChip = grid.querySelector('.vb-chip[data-val-id="' + data.id + '"]');
            if (newChip) toggleValue(newChip, attr, true);
          }
          newValInput.value = '';
          updateBadges();
        })
        .catch(function (e) { console.error(e); })
        .finally(function () { newValBtn.disabled = false; });
    });
    newValInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); newValBtn.click(); }
    });
  }

  /* ── Inline: create a NEW attribute type ─────────────────── */
  var newAttrToggle = document.getElementById('vb-new-attr-toggle');
  var newAttrRow    = document.getElementById('vb-new-attr-row');
  var newAttrInput  = document.getElementById('vb-new-attr-input');
  var newAttrBtn    = document.getElementById('vb-new-attr-btn');

  if (newAttrToggle && newAttrRow) {
    newAttrToggle.addEventListener('click', function () {
      newAttrRow.classList.toggle('d-none');
      if (!newAttrRow.classList.contains('d-none') && newAttrInput) newAttrInput.focus();
    });
  }

  if (newAttrBtn && newAttrInput) {
    newAttrBtn.addEventListener('click', function () {
      var name = newAttrInput.value.trim();
      if (!name) return;
      newAttrBtn.disabled = true;
      fetch('/attribute/inline-store', {
        method : 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN' : csrfToken,
          'Accept'       : 'application/json',
        },
        body: JSON.stringify({ name: name }),
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.error) { alert(data.error); return; }
          allAttrs.push({ id: data.id, name: data.name, main: 0, values: [] });
          renderPills();
          newAttrInput.value = '';
          if (newAttrRow) newAttrRow.classList.add('d-none');
          showPicker(data.id);
        })
        .catch(function (e) { console.error(e); })
        .finally(function () { newAttrBtn.disabled = false; });
    });
    newAttrInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); newAttrBtn.click(); }
    });
  }

  /* ── Variants table (Cartesian product) ──────────────────── */
  function renderTable() {
    var tbody     = document.getElementById('vb-tbody');
    var tableWrap = document.getElementById('vb-table-wrap');
    var emptyMsg  = document.getElementById('vb-empty-msg');
    if (!tbody) return;

    if (selected.size === 0) {
      if (tableWrap) tableWrap.classList.add('d-none');
      if (emptyMsg)  emptyMsg.classList.remove('d-none');
      tbody.innerHTML = '';
      return;
    }
    if (tableWrap) tableWrap.classList.remove('d-none');
    if (emptyMsg)  emptyMsg.classList.add('d-none');

    // Group selected values by attrId
    var groups = {};
    selected.forEach(function (data, valId) {
      if (!groups[data.attrId]) groups[data.attrId] = [];
      groups[data.attrId].push({
        valId   : valId,
        attrName: data.attrName,
        valName : data.valName,
        attrId  : data.attrId,
      });
    });

    var groupArrays = Object.values(groups);
    var combos      = cartesian(groupArrays);

    tbody.innerHTML = '';
    combos.forEach(function (combo, i) {
      var valueIds = combo.map(function (c) { return c.valId; });
      var key      = comboKey(valueIds);
      var price    = comboPrices.has(key) ? comboPrices.get(key) : 0;
      var stock    = comboStocks.has(key) ? comboStocks.get(key) : 0;
      var existId  = comboIds.has(key) ? (comboIds.get(key) || '') : '';

      var label = combo.map(function (c) {
        return c.attrName + ': ' + c.valName;
      }).join(' / ');

      var hiddenValues = valueIds.map(function (vid) {
        return '<input type="hidden" name="combos[' + i + '][values][]" value="' + vid + '">';
      }).join('');

      var tr = document.createElement('tr');
      tr.className = 'vb-row';
      tr.innerHTML =
        '<td style="padding:.4rem .6rem">' +
          '<span class="vb-variant-chip">' + label + '</span>' +
          hiddenValues +
          '<input type="hidden" name="combos[' + i + '][combination_id]" value="' + existId + '">' +
        '</td>' +
        '<td style="padding:.3rem .5rem;width:130px">' +
          '<input type="number" name="combos[' + i + '][price]" class="filter-input vb-input" ' +
            'value="' + price + '" min="0" step="1" placeholder="0 = base" ' +
            'data-combo-key="' + key + '" ' +
            'oninput="window.vbUpdateComboPrice(\'' + key + '\', this.value)">' +
        '</td>' +
        '<td style="padding:.3rem .5rem;width:100px">' +
          '<input type="number" name="combos[' + i + '][stock]" class="filter-input vb-input" ' +
            'value="' + stock + '" min="-1" step="1" ' +
            'data-combo-key="' + key + '" ' +
            'oninput="window.vbUpdateComboStock(\'' + key + '\', this.value)">' +
        '</td>' +
        '<td style="padding:.3rem .4rem;text-align:center;width:36px">' +
          '<button type="button" class="vb-del" data-val-ids="' + valueIds.join(',') + '" title="Quitar estas variantes">' +
            '<i class="fas fa-times"></i>' +
          '</button>' +
        '</td>';

      tr.querySelector('.vb-del').addEventListener('click', function () {
        var ids = this.dataset.valIds.split(',').map(Number);
        ids.forEach(function (vid) {
          selected.delete(vid);
          var chip = document.querySelector('.vb-chip[data-val-id="' + vid + '"]');
          if (chip) chip.classList.remove('selected');
        });
        renderTable();
        updateBadges();
      });

      tbody.appendChild(tr);
    });
  }

  /* ── Badge counters on pills ──────────────────────────────── */
  function updateBadges() {
    var counts = {};
    selected.forEach(function (d) {
      counts[d.attrId] = (counts[d.attrId] || 0) + 1;
    });
    allAttrs.forEach(function (a) {
      var b = document.getElementById('vb-badge-' + a.id);
      if (!b) return;
      var n = counts[a.id] || 0;
      b.textContent   = n;
      b.style.display = n > 0 ? '' : 'none';
    });
  }

  /* ── Public API ───────────────────────────────────────────── */
  window.vbUpdateComboPrice = function (key, val) {
    comboPrices.set(key, parseFloat(val) || 0);
  };
  window.vbUpdateComboStock = function (key, val) {
    comboStocks.set(key, parseInt(val) || 0);
  };
  window.vbSetAllStock = function (value) {
    document.querySelectorAll('input[name^="combos["][name$="[stock]"]').forEach(function (inp) {
      inp.value = value;
      var key = inp.dataset.comboKey;
      if (key) comboStocks.set(key, parseInt(value) || 0);
    });
  };
  window.vbRestoreStock = function () {
    document.querySelectorAll('input[name^="combos["][name$="[stock]"]').forEach(function (inp) {
      var key = inp.dataset.comboKey;
      if (!key) return;
      inp.value = comboStocks.has(key) ? comboStocks.get(key) : 0;
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
