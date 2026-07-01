// SPAMPK — skrip antara muka (tanpa kebergantungan luar talian)

document.addEventListener('DOMContentLoaded', () => {
    // Tambah/buang baris berulang (aset, limitasi, cadangan)
    document.querySelectorAll('[data-repeat]').forEach((group) => {
        const name = group.dataset.repeat;
        const list = group.querySelector('[data-repeat-list]');
        const tpl = group.querySelector('template');

        group.querySelector('[data-repeat-add]')?.addEventListener('click', () => {
            const idx = list.querySelectorAll('[data-repeat-item]').length;
            const html = tpl.innerHTML.replaceAll('__INDEX__', idx);
            const wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            list.appendChild(wrap.firstElementChild);
        });

        list.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-repeat-remove]');
            if (btn) btn.closest('[data-repeat-item]')?.remove();
        });
    });

    // Sahkan sebelum tindakan padam/nyahaktif
    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            if (!window.confirm(form.dataset.confirm)) e.preventDefault();
        });
    });

    // ---------- Drawer bar sisi (mudah alih) ----------
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const overlay = document.getElementById('sidebarOverlay');
    const MOBILE_BREAKPOINT = 920;

    if (sidebar && menuToggle && overlay) {
        const openSidebar = () => {
            sidebar.classList.add('is-open');
            overlay.classList.add('is-visible');
            document.body.classList.add('no-scroll');
            menuToggle.setAttribute('aria-expanded', 'true');
        };

        const closeSidebar = () => {
            sidebar.classList.remove('is-open');
            overlay.classList.remove('is-visible');
            document.body.classList.remove('no-scroll');
            menuToggle.setAttribute('aria-expanded', 'false');
        };

        menuToggle.addEventListener('click', openSidebar);
        sidebarClose?.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Tutup drawer bila tekan Esc
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                closeSidebar();
            }
        });

        // Tutup drawer secara automatik selepas pilih pautan menu (mudah alih)
        sidebar.querySelectorAll('.nav__link').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= MOBILE_BREAKPOINT) closeSidebar();
            });
        });

        // Pastikan drawer tertutup semula bila skrin dilebarkan ke saiz desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > MOBILE_BREAKPOINT) closeSidebar();
        });
    }
});