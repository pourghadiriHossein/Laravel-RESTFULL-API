<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Poulstar Vue API Course</title>
    <link rel="shortcut icon" href="{{ asset('default-assets/images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('default-assets/css/app.css') }}">
</head>
<body>
    <h1>Welcome to the Poulstar Vue API course</h1>
    <h2>You can use this guide for developing your app</h2>

    <table class="table">
        <tr class="header">
            <th class="head-first">Row</th>
            <th class="head-second">Title</th>
            <th class="head-third">Detail</th>
            <th class="head-fourth">More</th>
        </tr>
        <tr class="row">
            <td class="data">01</td>
            <td class="data">client id</td>
            <td class="data">1</td>
            <td class="data">-</td>
        </tr>
        <tr class="row">
            <td class="data">02</td>
            <td class="data">client secret</td>
            <td class="data">sL8r8yA2o3yGzbXYeb4xsg7Wie3RmKmUTOTFHVoI</td>
            <td class="data">-</td>
        </tr>
        <tr class="row">
            <td class="data">03</td>
            <td class="data">oauth link</td>
            <td class="data">oauth/token</td>
            <td class="data">username, password</td>
        </tr>
        <tr class="row">
            <td class="data">04</td>
            <td class="data">register link</td>
            <td class="data">api/register</td>
            <td class="data">name, email, password, avatar</td>
        </tr>
        <tr class="row">
            <td class="data">05</td>
            <td class="data">profile link</td>
            <td class="data">api/user/profile</td>
            <td class="data">-</td>
        </tr>
        <tr class="row">
            <td class="data">06</td>
            <td class="data">update my profile link</td>
            <td class="data">api/update-my-profile/{user}</td>
            <td class="data">user ID, name, email, password, avatar</td>
        </tr>
        <tr class="row">
            <td class="data">07</td>
            <td class="data">all posts for dashboard link</td>
            <td class="data">api/all-posts-for-dashboard</td>
            <td class="data">page, per_page</td>
        </tr>
        <tr class="row">
            <td class="data">08</td>
            <td class="data">like post link</td>
            <td class="data">api/posts/{post}/like</td>
            <td class="data">post ID</td>
        </tr>
        <tr class="row">
            <td class="data">09</td>
            <td class="data">see post detail link</td>
            <td class="data">api/view-post/{post}</td>
            <td class="data">post ID</td>
        </tr>
        <tr class="row">
            <td class="data">10</td>
            <td class="data">my posts link</td>
            <td class="data">api/my-posts</td>
            <td class="data">page, filter</td>
        </tr>
        <tr class="row">
            <td class="data">11</td>
            <td class="data">create post link</td>
            <td class="data">api/create-post</td>
            <td class="data">title, description, latitude, longitude, image</td>
        </tr>
        <tr class="row">
            <td class="data">12</td>
            <td class="data">update my post link</td>
            <td class="data">api/update-my-post/{post}</td>
            <td class="data">post ID, title, description, latitude, longitude, image</td>
        </tr>
        <tr class="row">
            <td class="data">13</td>
            <td class="data">delete method for remove my post</td>
            <td class="data">api/delete-my-post/{post}</td>
            <td class="data">post ID</td>
        </tr>
        <tr class="row">
            <td class="data">14</td>
            <td class="data">all posts for admin link</td>
            <td class="data">api/all-posts-for-admin</td>
            <td class="data">page, filter</td>
        </tr>
        <tr class="row">
            <td class="data">15</td>
            <td class="data">update post by admin link</td>
            <td class="data">api/update-post-by-admin/{post}</td>
            <td class="data">post ID, title, description, latitude, longitude, image</td>
        </tr>
        <tr class="row">
            <td class="data">16</td>
            <td class="data">delete method for remove post by admin</td>
            <td class="data">api/delete-post-by-admin/{post}</td>
            <td class="data">post ID</td>
        </tr>
        <tr class="row">
            <td class="data">17</td>
            <td class="data">all users link</td>
            <td class="data">api/all-users</td>
            <td class="data">page, filter</td>
        </tr>
        <tr class="row">
            <td class="data">18</td>
            <td class="data">create user by admin link</td>
            <td class="data">api/create-user-by-admin</td>
            <td class="data">name, email, password, avatar, role</td>
        </tr>
        <tr class="row">
            <td class="data">19</td>
            <td class="data">update user by admin link</td>
            <td class="data">api/update-user-by-admin/{user}</td>
            <td class="data">user ID, name, email, password, avatar, role</td>
        </tr>
        <tr class="row">
            <td class="data">19</td>
            <td class="data">delete method for remove user by admin</td>
            <td class="data">api/delete-user-by-admin/{user}</td>
            <td class="data">user ID</td>
        </tr>
        <tr class="row">
            <td class="data">20</td>
            <td class="data">search post by location</td>
            <td class="data">api/search-post</td>
            <td class="data">pageNumber, perPage, latitude, longitude</td>
        </tr>
        <tr class="row">
            <td class="data">21</td>
            <td class="data">get root comment for a post</td>
            <td class="data">api/comments</td>
            <td class="data">post, perPage</td>
        </tr>
        <tr class="row">
            <td class="data">22</td>
            <td class="data">create a comment for post or replay a comment</td>
            <td class="data">api/comments/{post}/create</td>
            <td class="data">post ID, parent_id, title, text</td>
        </tr>
        <tr class="row">
            <td class="data">23</td>
            <td class="data">get children comment from parent comment</td>
            <td class="data">api/comments/{comment}</td>
            <td class="data">comment ID</td>
        </tr>
        <tr class="row">
            <td class="data">24</td>
            <td class="data">put method for update comment data</td>
            <td class="data">api/comments/{comment}</td>
            <td class="data">comment ID, title, text</td>
        </tr>
        <tr class="row">
            <td class="data">25</td>
            <td class="data">delete method for remove a comment</td>
            <td class="data">api/comments/{comment}</td>
            <td class="data">comment ID</td>
        </tr>
    </table>
</body>
</html>
