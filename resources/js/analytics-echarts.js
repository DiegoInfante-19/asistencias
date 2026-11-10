import * as echarts from 'echarts';

export class AttendanceAnalyticsUI {
    constructor() {
        this.chartEmpresas = null;
        this.initInstances();
        this.initResizeListener();
    }

    initInstances() {
        const domEmpresas = document.getElementById('chartSemaforoEmpresa');
        if (domEmpresas) {
            this.chartEmpresas = echarts.init(domEmpresas);
        }
    }

    initResizeListener() {
        window.addEventListener('resize', () => {
            if (this.chartEmpresas) this.chartEmpresas.resize();
        });
    }

    /**
     * Renderiza el gráfico Stacked Horizontal Bar para el semáforo por empresas
     * @param {Object} empresasData - Objeto con el desglose de riesgo por empresa
     */
    renderCompanyStackedBar(empresasData) {
        if (!this.chartEmpresas) return;

        const empresas = Object.keys(empresasData);
        const seguros = empresas.map(emp => empresasData[emp].verde);
        const alertas = empresas.map(emp => empresasData[emp].amarillo);
        const reprobados = empresas.map(emp => empresasData[emp].rojo);

        const option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: { type: 'shadow' }
            },
            legend: {
                data: ['🟢 Seguro (<15%)', '🟡 Alerta (15-24.9%)', '🔴 Reprobado (>=25%)'],
                bottom: 0
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '12%',
                top: '5%',
                containLabel: true
            },
            xAxis: {
                type: 'value',
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: empresas,
                axisLabel: {
                    interval: 0,
                    fontSize: 11
                }
            },
            color: ['#198754', '#ffc107', '#dc3545'],
            series: [
                {
                    name: '🟢 Seguro (<15%)',
                    type: 'bar',
                    stack: 'total',
                    emphasis: { focus: 'series' },
                    data: seguros
                },
                {
                    name: '🟡 Alerta (15-24.9%)',
                    type: 'bar',
                    stack: 'total',
                    emphasis: { focus: 'series' },
                    data: alertas
                },
                {
                    name: '🔴 Reprobado (>=25%)',
                    type: 'bar',
                    stack: 'total',
                    emphasis: { focus: 'series' },
                    data: reprobados
                }
            ]
        };

        this.chartEmpresas.setOption(option, true);
    }
}