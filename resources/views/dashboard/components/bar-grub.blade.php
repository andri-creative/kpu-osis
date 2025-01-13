<div id="groupedBarChart"></div>
<script>
    // Ambil data dari Laravel Blade
    const classes = @json($groupedBarData['classes']);
    const candidateVotes = @json($groupedBarData['candidateVotes']);
    const candidateNames = @json($groupedBarData['candidateNames']);

    console.log('Classes:', classes);
    console.log('Candidate Votes:', candidateVotes);
    console.log('Candidate Names:', candidateNames);

    // Calculate total votes for each class
    const totalVotesPerClass = classes.map((_, classIndex) => {
        return candidateVotes.reduce((sum, votes) => sum + votes[classIndex], 0);
    });

    // Create array of classes with their total votes
    const classVotes = totalVotesPerClass.map((total, index) => ({
        index,
        total,
        name: classes[index]
    }));

    // Sort classes by total votes and get the top 5
    const topClasses = classVotes.sort((a, b) => b.total - a.total).slice(0, 5);

    // Prepare series for the top 5 classes
    const series = candidateVotes.map((votes, candidateIndex) => {
        // Only include votes for the top 5 classes
        return {
            name: candidateNames[candidateIndex],
            data: topClasses.map(topClass => votes[topClass.index])
        };
    });

    // Colors for top candidates
    const colors = ['#008FFB', '#FF4560', '#00E396', '#775DD0', '#FEB019', '#FF4500'];

    // Options for the chart
    const options = {
        chart: {
            type: 'bar',
        },
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: 'rounded',
            },
        },
        dataLabels: {
            enabled: true,
        },
        colors: colors,
        series: series,
        xaxis: {
            categories: topClasses.map(c => c.name) // Using only the top 5 classes
        },
        yaxis: {
            title: {
                text: 'Number of Votes'
            },
        },
        tooltip: {
            shared: true,
            intersect: false,
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
        }
    };

    // Render the chart
    const chart = new ApexCharts(document.querySelector("#groupedBarChart"), options);
    chart.render();
</script>
