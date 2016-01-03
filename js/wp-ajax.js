

function club_ajax_post(action, data, callback) {
    data['action'] = "club_"+action;
    jQuery.post(ajax_object.ajax_url, data, callback);
}