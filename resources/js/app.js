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
});
