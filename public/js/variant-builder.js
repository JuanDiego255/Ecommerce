/**
 * Shopify-style variant builder widget.
 * Reads data-attributes and data-existing from #vb-container,
 * renders pill selectors + chip grid + variants table,
 * and emits precios_attr[id] / cantidades_attr[id] / attr_id[id] inputs.
 *
 * Inline creation:
 *   - "Nuevo valor" input below the chip grid  → POST /attribute-value/inline-store
 *   - "Crear tipo" link below the pills        → POST /attribute/inline-store
 */
(function () {
  'use strict';

  var wrap = document.getElementById('vb-container');
  if (!wrap) return;

  var allAttrs = JSON.parse(wrap.dataset.attributes || '[]');
  var existing = JSON.parse(wrap.dataset.existing  || '[]');
  var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

  // Map<valueId:number, {attrId, attrName, valName, price, stock}>
  var selected    = new Map();
  var activeAttrId = null;

  /* ── Init ─────────────────────────────────────────────────── */
  function init() {
    existing.forEach(function (s) {
      if (!s.attr_id) return;
      var attr = allAttrs.find(function (a) { return a.id == s.attr_id; });
      if (!attr) return;
      var val = attr.values.find(function (v) { return v.id == s.value_attr; });
      if (!val) return;
      selected.set(parseInt(s.value_attr), {
        attrId  : parseInt(s.attr_id),
        attrName: attr.name,
        valName : val.value,
        price   : s.price  != null ? parseFloat(s.price)  : 0,
        stock   : s.stock  != null ? parseInt(s.stock)     : 0,
      });
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

    // Bind "Select all" button
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
      var ex = existing.find(function (e) { return parseInt(e.value_attr) === valId; });
      selected.set(valId, {
        attrId  : parseInt(chip.dataset.attrId),
        attrName: chip.dataset.attrName,
        valName : chip.dataset.valName,
        price   : ex ? (ex.price  != null ? parseFloat(ex.price)  : 0) : 0,
        stock   : ex ? (ex.stock  != null ? parseInt(ex.stock)     : 0) : 0,
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
          'X-CSRF-TOKEN': csrfToken,
          'Accept'      : 'application/json',
        },
        body: JSON.stringify({ attr_id: activeAttrId, value: val }),
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.error) { alert(data.error); return; }

          // Add to allAttrs in-memory
          var attr = allAttrs.find(function (a) { return a.id === activeAttrId; });
          if (attr) attr.values.push({ id: data.id, value: data.value });

          // Re-render chips and auto-select the new one
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
          'X-CSRF-TOKEN': csrfToken,
          'Accept'      : 'application/json',
        },
        body: JSON.stringify({ name: name }),
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.error) { alert(data.error); return; }

          // Add to allAttrs and rebuild pills
          allAttrs.push({ id: data.id, name: data.name, main: 0, values: [] });
          renderPills();
          newAttrInput.value = '';
          if (newAttrRow) newAttrRow.classList.add('d-none');

          // Open picker for the freshly created type
          showPicker(data.id);
        })
        .catch(function (e) { console.error(e); })
        .finally(function () { newAttrBtn.disabled = false; });
    });

    newAttrInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); newAttrBtn.click(); }
    });
  }

  /* ── Variants table ───────────────────────────────────────── */
  function renderTable() {
    var tbody    = document.getElementById('vb-tbody');
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

    tbody.innerHTML = '';
    selected.forEach(function (data, valId) {
      var tr = document.createElement('tr');
      tr.className = 'vb-row';

      tr.innerHTML =
        '<td style="padding:.4rem .6rem">' +
          '<span class="vb-variant-chip">' + data.attrName + ': ' + data.valName + '</span>' +
          '<input type="hidden" name="attr_id[' + valId + ']" value="' + data.attrId + '">' +
        '</td>' +
        '<td style="padding:.3rem .5rem;width:130px">' +
          '<input type="number" name="precios_attr[' + valId + ']" class="filter-input vb-input" value="' + (data.price || 0) + '" min="0" step="1" placeholder="0 = base">' +
        '</td>' +
        '<td style="padding:.3rem .5rem;width:100px">' +
          '<input type="number" name="cantidades_attr[' + valId + ']" class="filter-input vb-input" value="' + data.stock + '" min="-1" step="1">' +
        '</td>' +
        '<td style="padding:.3rem .4rem;text-align:center;width:36px">' +
          '<button type="button" class="vb-del" data-val-id="' + valId + '" title="Quitar variante">' +
            '<i class="fas fa-times"></i>' +
          '</button>' +
        '</td>';

      tr.querySelector('.vb-del').addEventListener('click', function () {
        var vid = parseInt(this.dataset.valId);
        selected.delete(vid);
        var chip = document.querySelector('.vb-chip[data-val-id="' + vid + '"]');
        if (chip) chip.classList.remove('selected');
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

  /* ── Public API for manage-stock toggle ───────────────────── */
  window.vbSetAllStock = function (value) {
    document.querySelectorAll('input[name^="cantidades_attr["]').forEach(function (inp) {
      inp.value = value;
    });
  };
  window.vbRestoreStock = function () {
    selected.forEach(function (data, valId) {
      var inp = document.querySelector('input[name="cantidades_attr[' + valId + ']"]');
      if (inp) inp.value = data.stock !== undefined ? data.stock : 0;
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
