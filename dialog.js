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
var test = window.location.search;
var degueux = test.search('id');
var degueux2 = test.search('page');
var page;

if (degueux >= 0) {
    test = test.substr(degueux);
    test = test.substr(test.search("=") + 1);
    id = test;
    showdialog();
}

if (degueux2 >= 0) {
    test = test.substr(degueux2);
    test = test.substr(test.search("=") + 1);
    page = test;
    console.log(page);
}

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
        div_delete.setAttribute('class', 'fa fa-trash');
        div_delete.setAttribute('onClick', 'askedDelete()');
        popup.insertBefore(div_delete, div_null);
        // console.log('Ce nom d user est bon, div creee');
    }
    // else
        // console.log('Ce nom user est bon mais la div a pas besoin detre cree');
}

function display_like(str) {
    document.getElementById('like').innerHTML = " "+parseInt(str);
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
            ret = req.responseText;
            str = ret.split(' ');
            console.log(str);
            display_image(str[5]);
            name = str[3];
            email = str[4];
            document.getElementById('user_name').innerHTML = "Image by : "+name;
            display_like(str[1]);
            display_comment(JSON.parse(str[2]));
            if (str[0] == "Ok") {
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
    // console.log(id);
    req.open("POST", "ajax.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            // console.log(req.responseText);
            ret = req.responseText;
            if (ret != 'Fail') {
                like.innerHTML = " "+parseInt(req.responseText);
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

        //
        p_name = document.createElement('p');
        p_name.setAttribute('class', 'p_name');
        //recuperer nom de l'utilisateur qui a commenté
        p_name_text = document.createTextNode(index.name+" :");
        p_name.appendChild(p_name_text);
       
        //
        p_comment = document.createElement('p');
        p_comment.setAttribute('class', 'p_comment');
        p_comment_text = document.createTextNode(decodeURIComponent(escape(window.atob(index.text))));
        p_comment.appendChild(p_comment_text);
        
        //
        div_comment.appendChild(p_name);
        div_comment.appendChild(p_comment);
        document.getElementById('comment_body').insertBefore(div_comment, document.getElementById('null42'));
    });
}

function askedComment() {
    var comment_text = document.getElementById('comment').value;
    b64 = btoa(unescape(encodeURIComponent(comment_text)));

    // console.log(comment_text, b64);
    if (comment_text != "") {
        req.open("POST", "ajax.php");
        req.onreadystatechange = function() {
            if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                ret = req.responseText;
                str = ret.split(' ');
                console.log(str);
                if (str[0] != 'Fail') {
                    div_comment = document.createElement('div');
                    div_comment.setAttribute('class', 'div_comment');
                    div_comment.setAttribute('title', str[0]);
                    div_comment.addEventListener("click", askedDeleteComment);
                    // div_comment.appendChild(div_comment_text);
                    //
                    p_name = document.createElement('p');
                    p_name.setAttribute('class', 'p_name');
                    //recuperer nom de l'utilisateur qui a commenté
                    p_name_text = document.createTextNode(str[1]+" :");
                    p_name.appendChild(p_name_text);
                
                    //
                    p_comment = document.createElement('p');
                    p_comment.setAttribute('class', 'p_comment');
                    p_comment_text = document.createTextNode(decodeURIComponent(escape(window.atob(b64))));
                    p_comment.appendChild(p_comment_text);
                    
                    //
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