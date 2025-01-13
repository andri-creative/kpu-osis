<div id="kandidatBarChart"></div>

<script>
    $(document).ready(function() {
        
        var kandidatData = @json($kandidatData);
        var pemilihPerKelas = @json($pemilihPerKelas);

        var labelsKandidat = kandidatData.map(function(kandidat) {
            return kandidat.name;
        });

        var dataVotesKandidat = kandidatData.map(function(kandidat) {
            return kandidat.votes;
        });

        const options = {
            chart: {
                type: 'bar',

            },
            series: [{
                name: 'Jumlah Suara',
                data: dataVotesKandidat 
            }],
            plotOptions: {
                bar: {
                    distributed: true,
                    horizontal: false,
                    borderRadius: 0, 
                    dataLabels: {
                        position: 'top', 
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: labelsKandidat,
                position: 'bottom',
                axisBorder: {
                    show: true
                },
                axisTicks: {
                    show: true
                },
            },
            title: {
                // text: 'Jumlah Suara per Kandidat',
                floating: true,
                offsetY: 0,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#kandidatBarChart"), options);
        chart.render();
    });
</script>
