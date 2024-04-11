$(document).ready(function() {
    // Event listener for the "Publish Results" button click
    $('.publishResult').on('click', function() {
        // Get the parent div of the clicked button
        var parentDiv = $(this).closest('.result');

        // Extract relevant data from the parent div
        
        var electionId = parentDiv.find('.eId').text().trim();
        var electionTitle = parentDiv.find('.eTitle').text().trim();
        var electionDate = parentDiv.find('.eDate').text().trim();
        var candidates = [];

        // Loop through each row in the table
        parentDiv.find('tr').each(function(index, element) {
            // Skip the header row (if you have one)
            if (index === 0) return;
            
            // Get candidate name
            var candidateName = $(element).find('td').eq(2).text().trim();
            
            // Get the vote count attribute
            var voteCountAttr = $(element).find('td').eq(3).data('votecount');

            if (typeof voteCountAttr !== 'undefined' && voteCountAttr !== '') {
                var voteCount = parseInt(voteCountAttr, 10);
            } else {
                console.error('Vote count is missing or invalid for candidate:', candidateName);
                return; // Skip this candidate if vote count is missing or invalid
            }
            
            
            // Retrieve the percentage attribute
            var percentageAttr = $(element).find('td').eq(4).data('percentage');
            
            // Check if the percentage attribute is defined and valid
            var percentage;
            if (typeof percentageAttr === 'string' && percentageAttr !== '') {
                // Use replace() only if percentageAttr is a string
                percentage = parseFloat(percentageAttr.replace('%', ''));
            } else {
                console.error('Percentage is missing or invalid for candidate:', candidateName);
                return; // Skip this candidate if percentage is missing
            }
            
            // Retrieve the image path
            var imagePath = $(element).find('td').eq(1).data('imagepath');
            
            // Push the candidate's data into the candidates array, including the image path
            candidates.push({
                candidateName: candidateName,
                voteCount: voteCount,
                percentage: percentage,
                imagePath: imagePath // Include the image path in the data
            });
        });

        // Prepare data to send via AJAX
        var data = {
            electionId: electionId,
            electionTitle: electionTitle,
            electionDate: electionDate,
            candidates: candidates
        };
        console.log('Data being sent:', data);

        // Perform AJAX request to store the results
        $.ajax({
            url: '../api/store_results.php',
            type: 'POST',
            data: JSON.stringify(data), // Ensure data is sent as JSON
            contentType: 'application/json', // Specify content type
            dataType: 'json',
            success: function(response) {
                // Handle success response
                console.log('Results published successfully:', response);
                alert('Result Publish sucessfully.');
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error('Error publishing results:', error);
            }
        });
    });
});
