/**
 * Tag Engine — client-side interactivity (demo, no backend calls).
 * Wires up: row/select-all checkboxes, tag action buttons, the sticky
 * selection bar, pagination and the "copy selected tags" button.
 */

// Action button markup for each selection state. Tailwind picks up these
// classes because resources/js is registered as a @source in app.css.
const ACTION = {
    selected: {
        class: 'inline-flex items-center justify-center gap-1 px-4 h-[42px] bg-magenta border-2 border-border-strong font-label text-[10px] uppercase text-on-magenta',
        html: '<svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Selected',
    },
    unselected: {
        class: 'inline-flex items-center justify-center px-3 h-[27px] bg-surface-muted border-2 border-border-strong font-label text-[10px] uppercase text-text-muted',
        html: '+ Add',
    },
};

const PAGE_ACTIVE = ['bg-text-primary', 'text-on-active'];
const PAGE_IDLE = ['bg-background', 'text-text-heading'];

function initTable() {
    const table = document.querySelector('[data-tag-table]');
    if (!table) return;

    const rows = Array.from(table.querySelectorAll('[data-tag-row]'));
    const checkAll = table.querySelector('[data-check-all]');
    const badge = document.querySelector('[data-selected-badge]');
    const tagList = document.querySelector('[data-selected-tags]');
    const copyBtn = document.querySelector('[data-copy]');

    const rowState = (row) => ({
        check: row.querySelector('[data-row-check]'),
        action: row.querySelector('[data-tag-action]'),
        name: row.dataset.tag,
    });

    function paintRow(row) {
        const { check, action } = rowState(row);
        const state = check.checked ? ACTION.selected : ACTION.unselected;
        action.className = state.class;
        action.innerHTML = state.html;
    }

    function selectedNames() {
        return rows.filter((r) => rowState(r).check.checked).map((r) => r.dataset.tag);
    }

    function sync() {
        const names = selectedNames();
        const total = rows.length;

        if (badge) badge.textContent = `${names.length} / ${total} selected`;
        if (tagList) tagList.textContent = names.length ? names.join(' · ') : '—';
        if (copyBtn) copyBtn.disabled = names.length === 0;

        if (checkAll) {
            checkAll.checked = names.length === total;
            checkAll.indeterminate = names.length > 0 && names.length < total;
        }
    }

    function setRow(row, checked) {
        rowState(row).check.checked = checked;
        paintRow(row);
    }

    rows.forEach((row) => {
        const { check, action } = rowState(row);
        paintRow(row);

        check.addEventListener('change', () => {
            paintRow(row);
            sync();
        });

        action.addEventListener('click', () => {
            setRow(row, !check.checked);
            sync();
        });
    });

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            rows.forEach((row) => setRow(row, checkAll.checked));
            sync();
        });
    }

    sync();
}

function initPagination() {
    const nav = document.querySelector('[data-pagination]');
    if (!nav) return;

    const pageButtons = Array.from(nav.querySelectorAll('[data-page]'));
    const prev = nav.querySelector('[data-page-prev]');
    const next = nav.querySelector('[data-page-next]');
    const showing = document.querySelector('[data-showing]');
    const pages = pageButtons.map((b) => Number(b.dataset.page));
    const totalTags = 245;
    const perPage = 10;

    let index = 0; // position within pageButtons

    function render() {
        pageButtons.forEach((btn, i) => {
            const active = i === index;
            btn.classList.toggle('bg-text-primary', active);
            btn.classList.toggle('text-on-active', active);
            btn.classList.toggle('bg-background', !active);
            btn.classList.toggle('text-text-heading', !active);
            btn.setAttribute('aria-current', active ? 'page' : 'false');
        });

        if (prev) prev.disabled = index === 0;
        if (next) next.disabled = index === pageButtons.length - 1;

        if (showing) {
            const page = pages[index];
            const start = (page - 1) * perPage + 1;
            const end = Math.min(page * perPage, totalTags);
            showing.textContent = `Showing ${start}-${end} of ${totalTags} Tags`;
        }
    }

    pageButtons.forEach((btn, i) => {
        btn.addEventListener('click', () => {
            index = i;
            render();
        });
    });

    if (prev) prev.addEventListener('click', () => {
        if (index > 0) { index--; render(); }
    });
    if (next) next.addEventListener('click', () => {
        if (index < pageButtons.length - 1) { index++; render(); }
    });

    render();
}

function initCopy() {
    const copyBtn = document.querySelector('[data-copy]');
    const label = document.querySelector('[data-copy-label]');
    const tagList = document.querySelector('[data-selected-tags]');
    if (!copyBtn || !label) return;

    let resetTimer;

    copyBtn.addEventListener('click', async () => {
        const text = tagList && tagList.textContent !== '—' ? tagList.textContent.replace(/ · /g, ', ') : '';
        if (!text) return;

        try {
            await navigator.clipboard.writeText(text);
            label.textContent = 'Copied!';
        } catch {
            label.textContent = 'Copy failed';
        }

        clearTimeout(resetTimer);
        resetTimer = setTimeout(() => { label.textContent = 'Copy Selected Tags'; }, 1500);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initTable();
    initPagination();
    initCopy();
});
