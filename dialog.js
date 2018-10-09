var img;
var id;
var src;
var div_delete = false;
var req = new XMLHttpRequest();
var comment = document.getElementById('comment');
var div_comment;
var div_like;

//if images
pictures = document.getElementsByTagName('img');
for (i = 0; i < pictures.length; i++) {
    pictures[i].addEventListener("click", showdialog);
}

function hide_div() {
    document.getElementById('transparant').style.visibility = "hidden";
    document.getElementById('hide').style.visibility = "hidden";
}

function display_delete() {
    if (!div_delete) {
        div_delete = document.createElement('div');
        div_delete.setAttribute('class', 'delete');
        div_delete.setAttribute('onClick', 'askedDelete()');
        div_delete_text = document.createTextNode('Delete the picture ?');
        div_delete.appendChild(div_delete_text);
        popup.insertBefore(div_delete, div_null);
        // console.log('Ce nom d user est bon, div creee');
    }
    // else
        // console.log('Ce nom user est bon mais la div a pas besoin detre cree');
}

function display_like(str) {
    if (div_like)
        popup.removeChild(div_like);
    div_like = document.createElement('div');
    div_like_text = document.createTextNode(parseInt(str));
    div_like.appendChild(div_like_text);
    popup.insertBefore(div_like, comment);
}

function display_comment(str) {
    // console.log(str);
    if (div_comment) {
        test = document.getElementsByClassName('delete_comment');
        for (index=test.length - 1; index > -1; index--)
            popup.removeChild(test[index]);
    }
    str.forEach(index => {
        div_comment = document.createElement('div');
        div_comment.setAttribute('class', 'delete_comment');
        div_comment.setAttribute('title', index.id_comment);
        div_comment.addEventListener("click", askedDeleteComment);
        div_comment_text = document.createTextNode(index.text);
        div_comment.appendChild(div_comment_text);
        popup.insertBefore(div_comment, comment);
    });
}

function getData() {
    req.open('POST', 'ajax.php');
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            // console.log(req.responseText);
            ret = req.responseText;
            str = ret.split(' ');
            console.log(str);
            str[2] = JSON.parse(str[2]);
            
            if (str[0] == "Ok") {
                //div delete affichee
                display_delete();
            }
            else if (div_delete) {
                popup.removeChild(div_delete);
                div_delete = false;
            }
            display_like(str[1]);
            //div like affichee
            // console.log(str[2])
            display_comment(str[2]);
        }
    }
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("picture_id="+id);
}

function showdialog(event) {
    id = event.currentTarget.alt;
    // console.log(id);
    if (id) {
        src = event.currentTarget.src;

        hide = document.getElementById('hide').style.visibility = "visible";
        transparant = document.getElementById('transparant').style.visibility = "visible";
        popup = document.getElementById('popup');
        div_null = document.getElementById('null');

        //console.log(src);
        img = document.getElementById('image_gallery');
        img.setAttribute('src', src);
        getData();
    }
}

function askedLike() {
    // console.log(id);
    req.open("POST", "ajax.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            // console.log(req.responseText);
            ret = req.responseText;

            if (ret != 'Fail') {
                popup.removeChild(div_like);
                div_like = document.createElement('span');
                div_like_text = document.createTextNode(parseInt(req.responseText));
                div_like.appendChild(div_like_text);
                popup.insertBefore(div_like, comment);
            }
            else if (ret == 'Fail')
                alert('Sorry, you need to sign it to like this picture ! (;');
        }
    };
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("like="+id);
}

function askedComment() {
    var comment_text = document.getElementById('comment').value;

    req.open("POST", "ajax.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            console.log(req.responseText);
            div_comment = document.createElement('div');
            div_comment.setAttribute('class', 'delete_comment');
            div_comment.setAttribute('title', req.responseText);
            div_comment.addEventListener("click", askedDeleteComment);
            div_comment_text = document.createTextNode(comment_text);
            div_comment.appendChild(div_comment_text);
            popup.insertBefore(div_comment, comment);
        }
    };
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("id="+id+"&comment="+comment_text);
}

function askedDelete() {
    answer = confirm('Are you sure you want to remove your picture ?');
    // console.log(id);

    if (answer) {
        req.open("POST", "dialogbox.php");
        req.onreadystatechange = function() {
            if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                document.location.href = "index.php";
            }
        };
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send( "id="+id);
    }
}

function askedDeleteComment(event) {
    console.log(event.currentTarget.title);
    comment_id = event.currentTarget.title;
    answer = confirm('Are you sure you want to remove your comment ?');

    if (answer) {
        req.open("POST", "ajax.php");
        req.onreadystatechange = function() {
            if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                console.log(req.responseText);
                if (req.responseText != 'Fail')
                    document.location.href = "index.php";
                else
                    alert("Sorry, you are not the owner of this comment.");
            }
        };
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send("delete_comment="+comment_id);
    }
}