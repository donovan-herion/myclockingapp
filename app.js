function refresh() {


    const fullDate = new Date();
    
    var hours = fullDate.getHours();
    if (hours<10)
    {
        hours = "0" + hours;
    }
    var mins = fullDate.getMinutes();
    if (mins<10)
    {
        mins = "0" + mins;
    }
    var secs = fullDate.getSeconds();
    if (secs<10)
    {
        secs = "0" + secs;
    }
    
    
    
    document.getElementById('hour').innerHTML = hours;
    document.getElementById('minute').innerHTML = ": " + mins;
    document.getElementById('second').innerHTML = ": " + secs;
    
    }
    
    setInterval(refresh, 1000);


    if (document.querySelector('.dis-commencer') != undefined) {
        let subcommencer = document.querySelector('.dis-commencer')
        
        subcommencer.addEventListener('click', () => {
            subcommencer.style.display = 'none'; //prevents user from submitting data multiple times
        })
    }

    if (document.querySelector('.dis-finir') != undefined) {
        let subfinir = document.querySelector('.dis-finir')

        subfinir.addEventListener('click', () => {
            subfinir.style.display = 'none'; //prevents user from submitting data multiple times
        })
    }

