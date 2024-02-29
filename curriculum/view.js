console.log('forward to '+ $('#curriculum_forward').html().replace('&amp;', '&'));

if ($('#curriculum_forward').length) {
    window.location.href = $('#curriculum_forward').html().replace('&amp;', '&');
}