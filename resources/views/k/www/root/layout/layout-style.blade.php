<style>

    article.readmore-content {
        /*height:360px;*/
    }

    article.readmore-content + a {
        position: relative;
        float:left;
        width:80px;
        padding:8px 8px 4px;
        margin-top:-40px;
        margin-left:calc(50% - 40px);
        text-align:center;
        border-right:4px;
        background:#eee;
    }


    .form-horizontal .has-feedback .form-control-feedback {
        right:15px;
        width:auto;
        padding-right:8px;
        color:#999;
    }
    .has-feedback .form-control {
        padding-right: 80px;
    }


    #switch {
        position: relative;
        width: 60px;
        height: 34px;
        background-color: #e7e7e7;
        border-radius: 34px;
        transition: background-color 0.3s;
    }

    #switch.on {
        background-color: #4caf50;
    }

    #switch .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #fff;
        transition: .4s;
        border-radius: 34px;
    }

    #switch:hover .slider {
        background-color: #ccc;
    }



    .main-header i {
        width:16px;
        vertical-align:middle;
    }
    .main-header .user-menu { height:50px; vertical-align:middle; }
    .main-header .user-text { vertical-align:middle; }


    @media (max-width: 991px) {

        .navbar-custom-menu .navbar-nav>li.user-menu>a {
            padding-top: 12px;
            padding-bottom: 12px;
            line-height: 26px;
            height: 100%;
            float:right;
        }

        .navbar-nav>.user-menu .user-image {
            float: left;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            margin-right: 0px;
            margin-top: 0px;
        }

    }


</style>