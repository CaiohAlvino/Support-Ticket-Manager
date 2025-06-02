document.addEventListener("DOMContentLoaded", function () {
    // Verificar se Chart.js está disponível
    if (typeof Chart === "undefined") {
        console.error("Chart.js não está carregado");
        return;
    }

    // =============== GRÁFICO DE STATUS ===============
    const statusChart = document.getElementById("ticketStatusChart");
    if (statusChart && window.ticketStatusData) {
        try {
            const statusData = window.ticketStatusData;

            // Preparar dados apenas com valores > 0
            const labels = [];
            const data = [];
            const colors = [];

            const statusConfig = {
                ABERTO: { label: "Aberto", color: "#198754" },
                AGUARDANDO_SUPORTE: { label: "Aguardando", color: "#ffc107" },
                FECHADO: { label: "Fechado", color: "#6c757d" },
                CRITICO: { label: "Crítico", color: "#dc3545" },
            };

            for (const [status, count] of Object.entries(statusData)) {
                if (count > 0 && statusConfig[status]) {
                    labels.push(statusConfig[status].label);
                    data.push(count);
                    colors.push(statusConfig[status].color);
                }
            }

            if (data.length > 0) {
                new Chart(statusChart.getContext("2d"), {
                    type: "doughnut",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                data: data,
                                backgroundColor: colors,
                                borderWidth: 2,
                                borderColor: "#ffffff",
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: "bottom",
                                labels: {
                                    boxWidth: 12,
                                    font: { size: 12 },
                                },
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return `${context.label}: ${context.parsed} (${percentage}%)`;
                                    },
                                },
                            },
                        },
                    },
                });
            } else {
                statusChart.parentElement.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-pie-chart display-6 mb-2"></i>
                        <p class="mb-0">Nenhum dado disponível</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error("Erro ao criar gráfico de status:", error);
            statusChart.parentElement.innerHTML = `
                <div class="text-center text-muted py-4">
                    <p class="mb-0">Erro ao carregar gráfico</p>
                </div>
            `;
        }
    }

    // =============== GRÁFICO DE USUÁRIOS ===============
    const userChart = document.getElementById("ticketUserChart");
    if (userChart && window.userTicketData) {
        try {
            const userLabels = window.userTicketData.labels || [];
            const userData = window.userTicketData.data || [];

            if (userLabels.length > 0 && userData.length > 0) {
                const userColors = [
                    "#0d6efd",
                    "#198754",
                    "#ffc107",
                    "#dc3545",
                    "#6610f2",
                    "#fd7e14",
                    "#20c997",
                    "#6c757d",
                ];

                new Chart(userChart.getContext("2d"), {
                    type: "doughnut",
                    data: {
                        labels: userLabels,
                        datasets: [
                            {
                                data: userData,
                                backgroundColor: userColors.slice(0, userLabels.length),
                                borderWidth: 2,
                                borderColor: "#ffffff",
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: "bottom",
                                labels: {
                                    boxWidth: 12,
                                    font: { size: 12 },
                                },
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return `${context.label}: ${context.parsed} tickets (${percentage}%)`;
                                    },
                                },
                            },
                        },
                    },
                });
            } else {
                userChart.parentElement.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-person-circle display-6 mb-2"></i>
                        <p class="mb-0">Nenhum responsável atribuído</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error("Erro ao criar gráfico de usuários:", error);
            userChart.parentElement.innerHTML = `
                <div class="text-center text-muted py-4">
                    <p class="mb-0">Erro ao carregar gráfico</p>
                </div>
            `;
        }
    }
});
