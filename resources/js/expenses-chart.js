import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

function chartColors() {
    const isDark = document.documentElement.classList.contains('dark');

    return {
        tick: isDark ? '#94a3b8' : '#64748b',
        grid: isDark ? 'rgba(148, 163, 184, 0.15)' : 'rgba(100, 116, 139, 0.15)',
        tooltipBg: isDark ? '#0f172a' : '#ffffff',
        tooltipText: isDark ? '#f8fafc' : '#1e293b',
        tooltipBorder: isDark ? '#334155' : '#e2e8f0',
    };
}

function buildChart(canvas, payload) {
    const colors = chartColors();
    const categories = payload.categories ?? [];

    return new Chart(canvas, {
        type: 'bar',
        data: {
            labels: categories.map((item) => item.label),
            datasets: [
                {
                    label: 'Gastos',
                    data: categories.map((item) => item.total),
                    backgroundColor: categories.map((item) => `${item.color}cc`),
                    borderColor: categories.map((item) => item.color),
                    borderWidth: 1,
                    borderRadius: 8,
                    maxBarThickness: 56,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: colors.tooltipBg,
                    titleColor: colors.tooltipText,
                    bodyColor: colors.tooltipText,
                    borderColor: colors.tooltipBorder,
                    borderWidth: 1,
                    callbacks: {
                        label(context) {
                            const value = context.parsed.y ?? 0;

                            return `S/ ${value.toLocaleString('es-PE', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            })}`;
                        },
                    },
                },
            },
            scales: {
                x: {
                    ticks: {
                        color: colors.tick,
                        maxRotation: 45,
                        minRotation: 0,
                    },
                    grid: {
                        display: false,
                    },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: colors.tick,
                        callback(value) {
                            return `S/ ${Number(value).toLocaleString('es-PE')}`;
                        },
                    },
                    grid: {
                        color: colors.grid,
                    },
                },
            },
        },
    });
}

function updateChartData(chart, payload) {
    const categories = payload.categories ?? [];

    chart.data.labels = categories.map((item) => item.label);
    chart.data.datasets[0].data = categories.map((item) => item.total);
    chart.data.datasets[0].backgroundColor = categories.map((item) => `${item.color}cc`);
    chart.data.datasets[0].borderColor = categories.map((item) => item.color);
    chart.update();
}

function initExpensesChart() {
    const root = document.getElementById('expenses-chart-root');

    if (!root) {
        return;
    }

    const endpoint = root.dataset.endpoint;
    const emptyMessage = root.dataset.emptyMessage ?? '';
    const labelEl = root.querySelector('[data-expenses-chart-label]');
    const bodyEl = root.querySelector('[data-expenses-chart-body]');
    const prevBtn = root.querySelector('[data-expenses-chart-prev]');
    const nextBtn = root.querySelector('[data-expenses-chart-next]');

    let navigation = {};

    try {
        navigation = JSON.parse(root.dataset.navigation || '{}');
    } catch {
        return;
    }

    let chart = null;
    let currentPayload = null;
    let loading = false;

    function getCanvas() {
        return document.getElementById('expenses-chart');
    }

    function renderEmptyState() {
        bodyEl.innerHTML = `
            <div data-expenses-chart-empty class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-800/40">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto size-12 text-slate-300 dark:text-slate-600" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">${emptyMessage}</p>
            </div>
        `;
    }

    function renderCanvasWrap() {
        bodyEl.innerHTML = `
            <div class="relative h-72 sm:h-80" data-expenses-chart-canvas-wrap>
                <canvas
                    id="expenses-chart"
                    aria-label="Gastos por categoría"
                    role="img"
                ></canvas>
            </div>
        `;
    }

    function updateNavigationControls() {
        nextBtn.disabled = !navigation.canGoNext;
    }

    function syncUrl(chartPayload) {
        const url = new URL(window.location.href);

        url.searchParams.set('mes', chartPayload.mes);
        url.searchParams.set('anio', chartPayload.anio);
        window.history.replaceState(null, '', url);
    }

    function applyChartPayload(payload) {
        currentPayload = payload;

        if (labelEl) {
            labelEl.textContent = payload.label ?? '';
        }

        const categories = payload.categories ?? [];
        const canvas = getCanvas();

        if (categories.length === 0) {
            if (chart) {
                chart.destroy();
                chart = null;
            }

            renderEmptyState();
            return;
        }

        if (!canvas) {
            renderCanvasWrap();
        }

        const activeCanvas = getCanvas();

        if (!activeCanvas) {
            return;
        }

        if (chart) {
            updateChartData(chart, payload);
            return;
        }

        chart = buildChart(activeCanvas, payload);
    }

    const canvas = getCanvas();

    if (canvas) {
        try {
            currentPayload = JSON.parse(canvas.dataset.chart || '{}');
            applyChartPayload(currentPayload);
        } catch {
            return;
        }
    }

    updateNavigationControls();

    const observer = new MutationObserver(() => {
        if (!chart || !currentPayload?.categories?.length) {
            return;
        }

        chart.destroy();
        chart = buildChart(getCanvas(), currentPayload);
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });

    async function loadMonth(params) {
        if (loading || !params?.mes || !params?.anio) {
            return;
        }

        loading = true;
        bodyEl.classList.add('opacity-60');

        try {
            const url = new URL(endpoint, window.location.origin);

            url.searchParams.set('mes', params.mes);
            url.searchParams.set('anio', params.anio);

            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            navigation = data.navigation ?? navigation;
            applyChartPayload(data.chart ?? {});
            updateNavigationControls();
            syncUrl(data.chart ?? params);
        } finally {
            loading = false;
            bodyEl.classList.remove('opacity-60');
        }
    }

    prevBtn?.addEventListener('click', () => {
        loadMonth(navigation.previous);
    });

    nextBtn?.addEventListener('click', () => {
        if (navigation.canGoNext) {
            loadMonth(navigation.next);
        }
    });

    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);
        const mes = url.searchParams.get('mes');
        const anio = url.searchParams.get('anio');

        if (mes && anio) {
            loadMonth({ mes, anio });
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initExpensesChart);
} else {
    initExpensesChart();
}
