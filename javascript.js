function autoRefreshCountNotification() {
    setInterval(function () {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                document.getElementById("count_noti").innerHTML = xhr.responseText.trim();
            }
        };
        xhr.open('GET', '../../database/auto_count_noti.php', true);
        xhr.send();
    }, 2000);
}

function autoRefreshNoticeNotification() {
    setInterval(function () {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                document.getElementById("notice_noti").innerHTML = xhr.responseText;
            }
        };
        xhr.open('GET', '../../database/auto_notice_noti.php', true);
        xhr.send();
    }, 2000);
}

function autoRefreshContentNotification() {
    setInterval(function () {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                document.getElementById("noti_content").innerHTML = xhr.responseText;
            }
        };
        xhr.open('GET', '../../database/auto_content_noti.php', true);
        xhr.send();
    }, 2000);
}

function updateStatus(id, action) {
    $.post('../../database/update_notification_status.php', { id: id, action: action }, function (response) {
        if (response === "success") {
            alert("Request " + action + " successfully.");
            autoRefreshContentNotification();
            autoRefreshCountNotification();
            autoRefreshNoticeNotification();
        } else {
            alert("Error updating request.");
        }
    });
}

// Start auto-refresh
autoRefreshCountNotification();
autoRefreshNoticeNotification();
autoRefreshContentNotification();
