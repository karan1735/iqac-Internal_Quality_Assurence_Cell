<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQAC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <!------------------------------------------------------------------------------------------------------------->
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
</head>

<body>
    <header id="header">
    </header>
    <nav id="navMenu">
        <div id="navbar"></div>
    </nav>
    <main>

        <h1>STRATEGIC PLAN</h1><br>
	<p>Kongu Engineering College has completed more than three decades of dedicated
		service in the field of technical education and has established a name for itself in
		offering high quality professional education. The college has developed and successfully
		implemented its first Strategic Plan for the period of five years (2015-2020) and a
		majority of the goals envisioned in the first plan were achieved. In order to further scale
		new heights in this highly competitive dynamic global scenario and to meet the
		expectations of the stakeholders, the college intended to renew the strategic plan for the
		duration of 2020-2025. Based on the results of implementation of the first strategic plan,
		a detailed SWOT Analysis was undertaken. After a thorough analysis and several
		deliberations, the new strategic plan has been developed.</p>
        <p>A strategic plan is a document that outlines an organization’s goals, objectives, and
            strategies for achieving success. It defines where the organization wants to go, how it will
            get there, and what resources it will need to allocate to achieve its objectives.</p>
        <p>A strategic plan for an educational institution typically includes:</p>
        <ol>
            <li>Mission and Vision statements: Define the institution’s purpose, values, and goals.</li>
            <li>SWOT Analysis: Identify Strengths, Weaknesses, Opportunities, and Threats to inform
                strategic decisions.</li>
            <li>Strategic Objectives: Set specific, measurable, achievable, relevant, and time-bound
                (SMART) goals.</li>
            <li>Academic Excellence: Enhance teaching, learning, research, and innovation.</li>
            <li>Student Experience: Foster a supportive, inclusive, and engaging environment.</li>
            <li>Infrastructure and Resources: Develop and maintain modern facilities, technology, and
                resources.</li>
            <li>Community Engagement: Build partnerships with local communities, industries, and
                organizations.</li>
            <li>Governance and Leaderships: Ensure effective management, leadership, and decision
                –making processes.</li>
            <li>Quality Assurance: Establish processes for continuous evaluation and improvement.</li>
            <li>Financial Sustainability: Ensure stable funding, resource allocation, and budget
                management.</li>
            <li>Marketing and Communication: Promote the institution’s brand, programs, and
                achievements.</li>
            <li>Monitoring and Evaluation: Regularly assess progress, identify areas for improvement,
                and adjust strategies accordingly.</li>
        </ol>

        <?php
                        $folderPath = '../files/Strategic Plan'; 
                        $files = scandir($folderPath);

            
                        echo "<div class='file-container'>";

                        rsort($files);
                        foreach ($files as $file){
                            
                            if ($file !== '.' && $file !== '..') {
                                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
                                            <i class='fa-regular fa-file-lines'></i> $file
                                        </a>";
                            }
                        }

                     
                        echo "</div>";
                        ?>

    </main>

</body>

</html>
</div>

</div>
</div>
</body>

</html>