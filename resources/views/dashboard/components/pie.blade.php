<div id="pieChart"></div>
<script>
    $(document).ready(function() {
        var kandidatData = @json($kandidatData);

        var labels = kandidatData.map(function(kandidat) {
            return kandidat.name;
        });

        var dataVotes = kandidatData.map(function(kandidat) {
            return kandidat.votes;
        });

        var options = {
            chart: {
                type: 'pie'
            },
            series: dataVotes,
            labels: labels,
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(2) + "%"
                },
                dropShadow: {
                    enabled: true,
                    top: 1,
                    left: 1,
                    blur: 2,
                    opacity: 0.7
                }
            },
            {{--  title: {
                text: 'Contoh Diagram Donat'
            }  --}}
        };

        var chart = new ApexCharts(document.querySelector("#pieChart"), options);
        chart.render();

    })
</script>
