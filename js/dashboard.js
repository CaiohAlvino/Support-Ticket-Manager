document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.ticketStatusData !== "object") return;
    const ctx = document.getElementById("ticketStatusChart").getContext("2d");
    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["ABERTO", "AGUARDANDO_SUPORTE", "FECHADO", "CRITICO"],
            datasets: [
                {
                    data: [
                        window.ticketStatusData.ABERTO || 0,
                        window.ticketStatusData.AGUARDANDO_SUPORTE || 0,
                        window.ticketStatusData.FECHADO || 0,
                        window.ticketStatusData.CRITICO || 0,
                    ],
                    backgroundColor: ["#0d6efd", "#ffc107", "#198754", "#dc3545"],
                    borderWidth: 1,
                },
            ],
        },
        options: { plugins: { legend: { position: "bottom" }, tooltip: { enabled: true } } },
    });

    if (
        window.userTicketData &&
        Array.isArray(window.userTicketData.labels) &&
        window.userTicketData.labels.length
    ) {
        const ctx2 = document.getElementById("ticketCategoryChart").getContext("2d");
        new Chart(ctx2, {
            type: "doughnut",
            data: {
                labels: window.userTicketData.labels,
                datasets: [
                    {
                        data: window.userTicketData.data,
                        backgroundColor: [
                            "#0d6efd",
                            "#ffc107",
                            "#198754",
                            "#dc3545",
                            "#6610f2",
                            "#fd7e14",
                            "#20c997",
                            "#6c757d",
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                plugins: {
                    legend: { position: "bottom" },
                    tooltip: { enabled: true },
                },
            },
        });
    }
});
