<div id="voteChart"></div>


{{--  <script>
    $(document).ready(function() {
        var ctx = $('#voteChart')[0].getContext('2d');

        
        var minutes = {!! json_encode($votesData->pluck('minute')) !!};
        var totalVotes = {!! json_encode($votesData->pluck('total_votes')) !!};

        console.log(minutes); // Untuk memastikan array minutes
        console.log(totalVotes); // Untuk memastikan array totalVotes

        // Render chart dengan Chart.js
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: minutes, // Label pada sumbu X (menit)
                datasets: [{
                    label: 'Jumlah Vote per Menit',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    data: totalVotes, // Data jumlah vote
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu (HH:mm)'    
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Vote'
                        }
                    }
                }
            }
        });
    });
</script>  --}}
<script>
    $(document).ready(function() {

        var minutes = {!! json_encode($votesData->pluck('minute')) !!};
        var totalVotes = {!! json_encode($votesData->pluck('total_votes')) !!};

        var options = {
            chart: {
                // height: 280,
                type: "area"
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: "Jumlah Vote per Menit",
                data: totalVotes
            }],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: minutes
            }
        };

        var chart = new ApexCharts(document.querySelector("#voteChart"), options);

        chart.render();
    });
</script>
