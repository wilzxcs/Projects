function startTime(){
    // Source code: w3schools
    const today = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    let h = today.getHours();
    let m = today.getMinutes();
    let s = today.getSeconds();

    m = checkTime(m);
    s = checkTime(s);

    let month = today.getMonth();
    let day = today.getDate();

    document.getElementById('timeNow').innerHTML = "<b>" + monthNames[month] + "</b> " + day + "<br>" +  h + ":" + m + ":" + s;
    setTimeout(startTime, 1000);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
function add_clone(){
    var html_ = $("#clones").html();
    var i = count(html_, "<tr>") + 2;
    var p_i = "player" + i;
    $("#clones").append("<tr><td> <label for='" + p_i + "'> Player " + i + " ID: </label></td>" +
                            "<td> <input type='text' name='" + p_i + "'> </td></tr>");
}

function count(main_str, sub_str) 
    {
    main_str += '';
    sub_str += '';

    if (sub_str.length <= 0) 
    {
        return main_str.length + 1;
    }

       subStr = sub_str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
       return (main_str.match(new RegExp(subStr, 'gi')) || []).length;
    }