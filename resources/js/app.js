import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


const channel = Echo.private(`App.Models.User.${userID}`);
channel.notification(function(data) {
    console.log(data.body);
    // alert(JSON.stringify(data));
});
