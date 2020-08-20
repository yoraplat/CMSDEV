const startHour = document.getElementById('edit_worksheet_form_startDate_time_hour');
const startMinute = document.getElementById('edit_worksheet_form_startDate_time_minute');

const startDay = document.getElementById('edit_worksheet_form_startDate_date_day');
const startMonth = document.getElementById('edit_worksheet_form_startDate_date_month');
const startYear = document.getElementById('edit_worksheet_form_startDate_date_year');


const stopHour = document.getElementById('edit_worksheet_form_endDate_time_hour');
const stopMinute = document.getElementById('edit_worksheet_form_endDate_time_minute');

const stopDay = document.getElementById('edit_worksheet_form_endDate_date_day');
const stopMonth = document.getElementById('edit_worksheet_form_endDate_date_month');
const stopYear = document.getElementById('edit_worksheet_form_endDate_date_year');


const timeInput = document.getElementById('edit_worksheet_form_time');
const timePause = document.querySelector('.time_pause');

document.querySelectorAll('.time_field').forEach(item => {
    item.addEventListener('change', (event) => {
        updateTime();
    }) 
});

function updateTime() {
    let pause = timePause.value;

    function zeroFill(number){
        let numZeros = 2;
        let n = Math.abs(number);
        let zeros = Math.max(0, numZeros - Math.floor(n).toString().length );
        let zeroString = Math.pow(10,zeros).toString().substr(1);
        if( number < 0 ) {
            zeroString = '-' + zeroString;
        }

    return zeroString+n;
    }

    let date1 = new Date(startYear.value, zeroFill(startMonth.value), zeroFill(startDay.value),  zeroFill(startHour.value), zeroFill(startMinute.value));
    let date2 = new Date(stopYear.value, zeroFill(stopMonth.value), zeroFill(stopDay.value), zeroFill(stopHour.value), zeroFill(stopMinute.value));
    
    if (date2 < date1) {
        date2.setDate(date2.getDate() + 1);
    }

    let diff = date2 - date1;

    timeInput.value = (diff / 60000) - pause;
}