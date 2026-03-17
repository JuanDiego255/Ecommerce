{{-- ── Quick-access Navbar ─────────────────────────────────── --}}
<style>
/* ── Quick Nav ───────────────────────────────────────────── */
.quick-nav {
    background: #007aff;
    display: flex;
    align-items: center;
    padding: 0 16px;
    height: 50px;
    gap: 10px;
    /* Mirror sidebar: my-3 ms-3 border-radius-xl (0.75rem ≈ 12px) */
    margin: 1rem 1rem 0 1rem;
    border-radius: 0.75rem;
    position: sticky;
    top: 1rem;
    z-index: 200;
    box-shadow: 0 4px 12px rgba(0,0,0,.12);
}

/* Toggle / hamburger */
.qnav-toggle {
    background: rgba(255,255,255,.15);
    border: none;
    border-radius: 8px;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #fff;
    transition: background .15s;
    flex-shrink: 0;
    text-decoration: none;
}
.qnav-toggle:hover { background: rgba(255,255,255,.28); color: #fff; }
.qnav-toggle .material-icons { font-size: 1.15rem; }

/* Brand */
.qnav-brand {
    font-size: .85rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -.01em;
    flex-shrink: 0;
    white-space: nowrap;
}

/* Divider */
.qnav-sep {
    width: 1px;
    height: 22px;
    background: rgba(255,255,255,.3);
    flex-shrink: 0;
}

/* Recents label */
.qnav-label {
    font-size: .67rem;
    font-weight: 600;
    color: rgba(255,255,255,.5);
    text-transform: uppercase;
    letter-spacing: .06em;
    flex-shrink: 0;
    white-space: nowrap;
}

/* Recent links container */
.qnav-recents {
    display: flex;
    align-items: center;
    gap: 5px;
    flex: 1;
    overflow: hidden;
    min-width: 0;
}

/* Each recent link */
.qnav-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 600;
    color: rgba(255,255,255,.88);
    text-decoration: none;
    background: rgba(255,255,255,.14);
    white-space: nowrap;
    border: 1.5px solid rgba(255,255,255,.22);
    transition: background .14s, color .14s, border-color .14s;
    flex-shrink: 0;
}
.qnav-link:hover {
    background: rgba(255,255,255,.28);
    color: #fff;
    border-color: rgba(255,255,255,.45);
    text-decoration: none;
}
.qnav-link.qnav-active {
    background: rgba(255,255,255,.28);
    color: #fff;
    border-color: rgba(255,255,255,.55);
}
.qnav-link .material-icons { font-size: .8rem; }

/* Empty state */
.qnav-empty {
    font-size: .72rem;
    color: rgba(255,255,255,.38);
    font-style: italic;
}

/* Right side */
.qnav-right {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: auto;
    flex-shrink: 0;
}

/* User avatar */
.qnav-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(255,255,255,.22);
    border: 1.5px solid rgba(255,255,255,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .72rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0;
}
.qnav-username {
    font-size: .78rem;
    font-weight: 600;
    color: rgba(255,255,255,.88);
    white-space: nowrap;
}

/* Responsive */
@media (max-width: 900px) {
    .qnav-label   { display: none; }
    .qnav-brand   { display: none; }
    .qnav-sep     { display: none; }
}
@media (max-width: 620px) {
    .qnav-link span { display: none; }
    .qnav-link { padding: 5px 9px; }
    .qnav-username { display: none; }
}
</style>

<nav class="quick-nav" aria-label="Navegación rápida">

    {{-- Hamburger (toggle sidebar) --}}
    <a href="javascript:;" class="qnav-toggle" id="iconNavbarSidenav" aria-label="Menú">
        <i class="material-icons">menu</i>
    </a>

    {{-- Brand --}}
    <span class="qnav-brand">
        {{ isset($tenantinfo->title) ? \Str::limit($tenantinfo->title, 18) : 'Admin' }}
    </span>

    <div class="qnav-sep"></div>
    <span class="qnav-label">Recientes</span>

    {{-- Recent module links — filled by JS --}}
    <div class="qnav-recents" id="qnav-recents"></div>

    {{-- User info --}}
    <div class="qnav-right">
        @auth
        <div class="qnav-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
        <span class="qnav-username">{{ Auth::user()->name ?? '' }}</span>
        @endauth
    </div>
</nav>

<script>
(function () {
    'use strict';

    /* ── Route → Module mapping ─────────────────────────────── */
    var MODULES = [
        { re: /buys-admin/,                         name: 'Pedidos',       icon: 'receipt_long'         },
        { re: /dashboard|^\/admin\/?$/,             name: 'Dashboard',     icon: 'dashboard'            },
        { re: /categories/,                         name: 'Categorías',    icon: 'category'             },
        { re: /clothing|new-item|add-item/,         name: 'Productos',     icon: 'inventory_2'          },
        { re: /users/,                              name: 'Usuarios',      icon: 'people'               },
        { re: /settings|config/,                    name: 'Configuración', icon: 'settings'             },
        { re: /blog/,                               name: 'Blog',          icon: 'article'              },
        { re: /testimonials/,                       name: 'Testimonios',   icon: 'star'                 },
        { re: /roles/,                              name: 'Roles',         icon: 'admin_panel_settings' },
        { re: /gifts/,                              name: 'Regalos',       icon: 'card_giftcard'        },
        { re: /services|barber/,                    name: 'Servicios',     icon: 'content_cut'          },
        { re: /attributes/,                         name: 'Atributos',     icon: 'tune'                 },
        { re: /bitacora|moves|logs/,                name: 'Bitácora',      icon: 'history'              },
        { re: /coupons|cupones/,                    name: 'Cupones',       icon: 'local_offer'          },
        { re: /total-buys/,                         name: 'Ventas',        icon: 'bar_chart'            },
    ];

    var KEY     = 'qnav_recent';
    var MAX     = 5;

    function detect(path) {
        for (var i = 0; i < MODULES.length; i++) {
            if (MODULES[i].re.test(path)) return MODULES[i];
        }
        return null;
    }

    function load() {
        try { return JSON.parse(localStorage.getItem(KEY)) || []; }
        catch (e) { return []; }
    }

    function save(list) {
        try { localStorage.setItem(KEY, JSON.stringify(list)); }
        catch (e) {}
    }

    function record() {
        var path = window.location.pathname;
        var mod  = detect(path);
        if (!mod) return;

        var list = load().filter(function (m) { return m.url !== path; });
        list.unshift({ name: mod.name, icon: mod.icon, url: path });
        if (list.length > MAX) list = list.slice(0, MAX);
        save(list);
    }

    function render() {
        var el   = document.getElementById('qnav-recents');
        if (!el) return;

        var list = load();
        var cur  = window.location.pathname;

        if (!list.length) {
            el.innerHTML = '<span class="qnav-empty">Sin historial aún</span>';
            return;
        }

        el.innerHTML = list.map(function (m) {
            var active = m.url === cur ? ' qnav-active' : '';
            return '<a href="' + m.url + '" class="qnav-link' + active + '">' +
                '<i class="material-icons">' + m.icon + '</i>' +
                '<span>' + m.name + '</span>' +
                '</a>';
        }).join('');
    }

    document.addEventListener('DOMContentLoaded', function () {
        record();
        render();
    });
}());
</script>
