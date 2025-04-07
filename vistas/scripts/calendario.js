const calendar = document.getElementById("fecha_hora");
const currentDate = new Date();

    calendar.min = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() - 2).toISOString().substring(0, 10);
    //calendar.max = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate()).toISOString().substring(0, 10);
    calendar.value = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate()).toISOString().substring(0, 10);

