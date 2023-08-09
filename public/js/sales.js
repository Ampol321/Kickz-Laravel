const ctx = document.getElementById('salesChart');

$.ajax({
    url: '/api/salesChart',
    type: 'GET',
    dataType: "json",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (data) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: 'Overall Sales',
                    data: Object.values(data),
                    fill: false,
                    borderColor: 'rgb(21, 21, 21)',
                    tension: 0.1
                }]
            },
            options: {
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 0,
                        to: 1,
                        loop: true
                    }
                },
            }
        });
    },
    error: function () {

    }
})