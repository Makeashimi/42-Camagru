var img;
var id;
var src;
var div_delete = false;
var req = new XMLHttpRequest();
var comment = document.getElementById('comment');
var div_comment;
var div_like;
var email;
var name;
var link = window.location.search;
var link_id = link.search('id');
var link_page = link.search('page');
var page;

escapeHTML = function(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
};

if (link_id >= 0) {
    str = link.substr(link_id);
    id = str.substr(str.search("=") + 1);
    showdialog();
}

if (link_page >= 0) {
    str = link.substr(link_page);
    if (link_id >= 0)
        page = str.substring(str.search("=") + 1, str.indexOf('&'));
    else
        page = str.substr(str.search("=") + 1);
}

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
        div_delete.setAttribute('class', 'fa fa-trash');
        div_delete.setAttribute('onClick', 'askedDelete()');
        popup.insertBefore(div_delete, div_null);
    }
}

function display_like(str, color_choice) {
    document.getElementById('like').innerHTML = " "+parseInt(str);
    document.getElementById('like').style.color = color_choice;
}

function display_image(src) {
    if (src == "") {
        document.location.href = "index.php";
        return ;
    }
    img = document.getElementById('image_gallery');
    img.setAttribute('src', src);
}

function getData() {
    req.open('POST', 'ajax.php');
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            ret = JSON.parse(req.responseText);
            // console.log(ret);
            display_image(ret.src);
            name = ret.name;
            email = ret.email;
            document.getElementById('user_name').innerHTML = "Image by : "+escapeHTML(ret.name);
            display_like(ret.like, ret.color);
            display_comment(ret.array);
            if (ret.ret == "Ok") {
                display_delete();
            }
            else if (div_delete) {
                popup.removeChild(div_delete);
                div_delete = false;
            }
        }
    }
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("picture_id="+id);
}

function showdialog(event) {
    if (event)
        id = event.currentTarget.alt;

    if (id) {
        hide = document.getElementById('hide').style.visibility = "visible";
        transparant = document.getElementById('transparant').style.visibility = "visible";
        popup = document.getElementById('popup');
        div_null = document.getElementById('null');
        getData();
    }
}

function askedLike() {
    like = document.getElementById('like');
    req.open("POST", "ajax.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            // console.log(req.responseText);
            ret = req.responseText;
            str = ret.split(' ');
            if (ret != 'Fail') {
                like.innerHTML = " "+parseInt(str[0]);
                like.style.color = str[1];
            }
            else if (ret == 'Fail')
                alert('Sorry, you need to sign it to like this picture ! (;');
        }
    };
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("like="+id);
}

function display_comment(str) {
    if (div_comment) {
        test = document.getElementsByClassName('div_comment');
        for (index=test.length - 1; index > -1; index--) {
            document.getElementById('comment_body').removeChild(test[index]);
        }
    }
    str.forEach(index => {
        div_comment = document.createElement('div');
        div_comment.setAttribute('class', 'div_comment');
        div_comment.setAttribute('title', index.id_comment);
        div_comment.addEventListener("click", askedDeleteComment);

        p_name = document.createElement('p');
        p_name.setAttribute('class', 'p_name');
        p_name_text = document.createTextNode(index.name+" :");
        p_name.appendChild(p_name_text);

        p_comment = document.createElement('p');
        p_comment.setAttribute('class', 'p_comment');
        p_comment_text = document.createTextNode(atob(index.text));
        p_comment.appendChild(p_comment_text);

        div_comment.appendChild(p_name);
        div_comment.appendChild(p_comment);
        document.getElementById('comment_body').insertBefore(div_comment, document.getElementById('null42'));
    });
}

function askedComment() {
    var comment_text = document.getElementById('comment').value;

    console.log(page);
    if (comment_text.length > 500) {
        alert('I told you it was max 500 char !');
        document.getElementById('comment').value = '';
    }
    else {
        b64 = btoa(comment_text);
        if (comment_text != "") {
            req.open("POST", "ajax.php");
            req.onreadystatechange = function() {
                if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                    ret = JSON.parse(req.responseText);
                    // console.log("REGQRDE", ret);
                    if (ret != 'Fail') {
                        div_comment = document.createElement('div');
                        div_comment.setAttribute('class', 'div_comment');
                        div_comment.setAttribute('title', ret.id_comment);
                        div_comment.addEventListener("click", askedDeleteComment);
                        
                        p_name = document.createElement('p');
                        p_name.setAttribute('class', 'p_name');
                        p_name_text = document.createTextNode(ret.name+" :");
                        p_name.appendChild(p_name_text);
                    
                        p_comment = document.createElement('p');
                        p_comment.setAttribute('class', 'p_comment');
                        p_comment_text = document.createTextNode(atob(b64));
                        p_comment.appendChild(p_comment_text);
                        
                        div_comment.appendChild(p_name);
                        div_comment.appendChild(p_comment);
                        document.getElementById('comment_body').insertBefore(div_comment, document.getElementById('null42'));
                        document.getElementById('comment').value = '';
                    }
                    else {
                        alert('Sorry, you need to sign it to comment this picture ! (;')
                        document.getElementById('comment').value = '';
                    }
                }
            };
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.send("page="+page+"&id="+id+"&comment="+b64+"&name="+name+"&email="+email);
        }
    }
}

function askedDelete() {
    answer = confirm('Are you sure you want to remove your picture ?');
    // console.log(id);

    if (answer) {
        req.open("POST", "ajax.php");
        req.onreadystatechange = function() {
            if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                if (req.responseText != 'Fail')
                    document.location.href = "index.php";
                else
                    alert('Sorry, you need to sign it to delete this picture ! (;')
            }
        };
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send("delete_picture="+id);
    }
}

function askedDeleteComment(event) {
    // console.log(event.currentTarget.title);'
    comment_id = event.currentTarget.title;
    answer = confirm('Are you sure you want to remove your comment ?');

    if (answer) {
        req.open("POST", "ajax.php");
        req.onreadystatechange = function() {
            if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                // console.log(req.responseText);
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