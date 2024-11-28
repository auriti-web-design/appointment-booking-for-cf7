(function ($) {
    $.fn.abcf7_calendar = function () {
        return this.each(function () {
            var $calendar = $(this);
            var $header = $calendar.find('.abcf7_calendar_header');
            var $monthYear = $header.find('.abcf7_calendar_month_year');
            var $prevButton = $header.find('.abcf7_calendar_prev');
            var $nextButton = $header.find('.abcf7_calendar_next');
            var $table = $calendar.find('.abcf7_calendar_table');

            var currentDate = new Date();
            var currentMonth = currentDate.getMonth();
            var currentYear = currentDate.getFullYear();

            // Function to display the calendar.
            function displayCalendar(month, year) {
                // Set the month and year in the header.
                $monthYear.text(getMonthName(month) + ' ' + year);

                // Create the calendar.
                var html = '<thead><tr>';
                var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                for (var i = 0; i < days.length; i++) {
                    html += '<th>' + days[i] + '</th>';
                }
                html += '</tr></thead><tbody>';

                // Calculate the first day of the month.
                var firstDay = (new Date(year, month)).getDay();

                // Calculate the number of days in the month.
                var daysInMonth = 32 - new Date(year, month, 32).getDate();

                // Create the rows and cells of the calendar.
                var date = 1;
                for (var i = 0; i < 6; i++) {
                    html += '<tr>';
                    for (var j = 0; j < 7; j++) {
                        if (i === 0 && j < firstDay) {
                            html += '<td></td>';
                        } else if (date > daysInMonth) {
                            html += '<td></td>';
                        } else {
                            html += '<td data-date="' + date + '">' + date + '</td>';
                            date++;
                        }
                    }
                    html += '</tr>';
                }

                html += '</tbody>';
                $table.html(html);

                // Send an AJAX request to get availability.
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'abcf7_get_appointment_availability',
                        month: month + 1, // JavaScript uses months from 0 to 11
                        year: year,
                        security: abcf7_ajax_object.security // Add nonce for security
                    },
                    success: function (response) {
                        // TODO: Update the calendar with the availability received from the server.
                        console.log(response);
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }

            // Function to get the month name.
            function getMonthName(month) {
                var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                return monthNames[month];
            }

            // Display the initial calendar.
            displayCalendar(currentMonth, currentYear);

            // Handle click on the "previous month" button.
            $prevButton.click(function () {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                displayCalendar(currentMonth, currentYear);
            });

            // Handle click on the "next month" button.
            $nextButton.click(function () {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                displayCalendar(currentMonth, currentYear);
            });
        });
    };
})(jQuery);