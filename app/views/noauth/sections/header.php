<?php
/**
 * Created by PhpStorm.
 * User: ruturaj
 * Date: 14/6/17
 * Time: 1:06 PM
 */
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-sm-4 col-xl-2 header-left">
        <div class="logo pull-left">
            <a href="#"><img src="<?= Url::to('@web/themes/assets/global/image/web-logo.png') ?>"
                             alt="logo"></a>
        </div>
        <button class="left-menu-toggle flat-buttons pull-right">
            <i class="icon_menu toggle-icon"></i>
        </button>
        <button class="right-menu-toggle flat-buttons pull-right">
            <i class="arrow_carrot-left toggle-icon"></i>
        </button>
    </div>

    <div class="col-sm-8 col-xl-10 header-right">
        <div class="header-inner-right">
            <div class="float-default searchbox">
                <div class="right-icon">
                    <a href="javascript:void(0)">
                        <i class="icon_search"></i>
                    </a>
                </div>
            </div>
            <div class="float-default mail">
                <div class="right-icon">
                    <a href="javascript:void(0)" data-toggle="dropdown"
                       data-open="true" aria-expanded="true">
                        <i class="icon_mail_alt"></i>
                        <span class="mail-no">10</span>
                    </a>
                    <div class="dropdown-menu messagetoggle" role="menu">
                        <div class="nav-tab-horizontal">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link flat-buttons active"
                                       data-toggle="tab" href="#messages"
                                       role="tab">Message</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link flat-buttons"
                                       data-toggle="tab" href="#resendmessage"
                                       role="tab">Resend</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="messages"
                                 role="tabpanel" data-plugin="custom-scroll"
                                 data-height="320">
                                <ul class="userMessagedrop">
                                    <li>
                                        <a href="#">
                                            <div class="media">
                                                <div class="media-left pull-left">
                                                        <span class="label label-primary"><i
                                                                    class="icon_plus"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <h6>New tasks added</h6>
                                                    <p>Dummy text of the
                                                        printing and typesetting
                                                        industry.</p>
                                                    <div class="meta-tag text-nowrap">
                                                        <time datetime="2016-12-10T20:27:48+07:00"
                                                              class="text-muted">
                                                            5 mins
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="media">
                                                <div class="media-left pull-left">
                                                        <span class="label label-success"><i
                                                                    class="icon_lock"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <h6>Successfully</h6>
                                                    <p>Dummy text of the
                                                        printing and typesetting
                                                        industry.</p>
                                                    <div class="meta-tag text-nowrap">
                                                        <time datetime="2016-12-10T20:27:48+07:00"
                                                              class="text-muted">
                                                            5 mins
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="media">
                                                <div class="media-left pull-left">
                                                        <span class="label label-danger"><i
                                                                    class="icon_info_alt"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <h6>Warnind</h6>
                                                    <p>Dummy text of the
                                                        printing and typesetting
                                                        industry.</p>
                                                    <div class="meta-tag text-nowrap">
                                                        <time datetime="2016-12-10T20:27:48+07:00"
                                                              class="text-muted">
                                                            5 mins
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="media">
                                                <div class="media-left pull-left">
                                                    <span class="label label-info"><i
                                                                class="icon_plus"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <h6>Add new friend</h6>
                                                    <p>Dummy text of the
                                                        printing and typesetting
                                                        industry.</p>
                                                    <div class="meta-tag text-nowrap">
                                                        <time datetime="2016-12-10T20:27:48+07:00"
                                                              class="text-muted">
                                                            5 mins
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-pane" id="resendmessage"
                                 role="tabpanel" data-plugin="custom-scroll"
                                 data-height="320">
                                <ul class="userMessagedrop">
                                    <li>
                                        <a href="#">
                                            <div class="media">
                                                <div class="media-left pull-left">
                                                        <span class="label label-primary"><i
                                                                    class="icon_profile"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <h6>5 new members
                                                        joi...</h6>
                                                    <p>Dummy text of the
                                                        printing and typesetting
                                                        industry.</p>
                                                    <div class="meta-tag text-nowrap">
                                                        <time datetime="2016-12-10T20:27:48+07:00"
                                                              class="text-muted">
                                                            2 mins
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="media">
                                                <div class="media-left pull-left">
                                                        <span class="label label-success"><i
                                                                    class="icon_key"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <h6>You changed...</h6>
                                                    <p>Dummy text of the
                                                        printing and typesetting
                                                        industry.</p>
                                                    <div class="meta-tag text-nowrap">
                                                        <time datetime="2016-12-10T20:27:48+07:00"
                                                              class="text-muted">
                                                            5 mins
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="media">
                                                <div class="media-left pull-left">
                                                        <span class="label label-danger"><i
                                                                    class="icon_close"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <h6>5 members removed</h6>
                                                    <p>Dummy text of the
                                                        printing and typesetting
                                                        industry.</p>
                                                    <div class="meta-tag text-nowrap">
                                                        <time datetime="2016-12-10T20:27:48+07:00"
                                                              class="text-muted">
                                                            15 mins
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="media">
                                                <div class="media-left pull-left">
                                                    <span class="label label-info"><i
                                                                class="icon_plus"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <h6>Update available</h6>
                                                    <p>Dummy text of the
                                                        printing and typesetting
                                                        industry.</p>
                                                    <div class="meta-tag text-nowrap">
                                                        <time datetime="2016-12-10T20:27:48+07:00"
                                                              class="text-muted">
                                                            5 mins
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="float-default chat">
                <div class="right-icon">
                    <a href="javascript:void(0)" data-toggle="dropdown"
                       data-open="true" data-animation="slideOutUp"
                       aria-expanded="false">
                        <i class="icon_chat_alt"></i>
                        <span class="mail-no">8</span>
                    </a>
                    <ul class="dropdown-menu userChat"
                        data-plugin="custom-scroll" data-height="310">
                        <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left pull-left">
                                        <img src="/themes/assets/global/image/img_400x400.png"
                                             alt="message"/>
                                    </div>
                                    <div class="media-body">
                                        <h5>Judy Fowler</h5>
                                        <p>Dummy text of the printing...</p>
                                        <div class="meta-tag text-nowrap">
                                            <time datetime="2016-12-10T20:27:48+07:00"
                                                  class="text-muted">5 mins
                                            </time>
                                        </div>
                                        <div class="status online"></div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left pull-left">
                                        <img src="/themes/assets/global/image/img_400x400.png"
                                             alt="message"/>
                                    </div>
                                    <div class="media-body">
                                        <h5>Judy Fowler</h5>
                                        <p>Dummy text of the printing...</p>
                                        <div class="meta-tag text-nowrap">
                                            <time datetime="2016-12-10T20:27:48+07:00"
                                                  class="text-muted">2 hours
                                            </time>
                                        </div>
                                        <div class="status offline"></div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left pull-left">
                                        <img src="/themes/assets/global/image/img_400x400.png"
                                             alt="message"/>
                                    </div>
                                    <div class="media-body">
                                        <h5>Judy Fowler</h5>
                                        <p>Dummy text of the printing...</p>
                                        <div class="meta-tag text-nowrap">
                                            <time datetime="2016-12-10T20:27:48+07:00"
                                                  class="text-muted">20 Oct
                                            </time>
                                        </div>
                                        <div class="status offline"></div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left pull-left">
                                        <img src="/themes/assets/global/image/img_400x400.png"
                                             alt="message"/>
                                    </div>
                                    <div class="media-body">
                                        <h5>Judy Fowler</h5>
                                        <p>Dummy text of the printing...</p>
                                        <div class="meta-tag text-nowrap">
                                            <time datetime="2016-12-10T20:27:48+07:00"
                                                  class="text-muted">20 Oct
                                            </time>
                                        </div>
                                        <div class="status online"></div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="float-default chat">
                <div class="right-icon">
                    <a href="#" data-plugin="fullscreen">
                        <i class="arrow_expand"></i>
                    </a>
                </div>
            </div>
            <div class="user-dropdown">
                <div class="btn-group">
                    <a href="#" class="user-header dropdown-toggle"
                       data-toggle="dropdown"
                       data-animation="slideOutUp" aria-haspopup="true"
                       aria-expanded="false">
                        <img src="/themes/assets/global/image/img_128x128.png"
                             alt="Profile image"/>
                    </a>
                    <div class="dropdown-menu drop-profile">
                        <div class="userProfile">
                            <img src="/themes/assets/global/image/img_128x128.png"
                                 alt="Profile image"/>
                            <h5>Miler Hussey</h5>
                            <p>milerhussey@yahoo.com</p>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="btn left-spacing link-btn flat-buttons"
                           href="#" role="button">Link</a>
                        <a class="btn left-second-spacing link-btn flat-buttons"
                           href="#" role="button">Link 2</a>
                        <a class="btn btn-primary pull-right right-spacing flat-buttons"
                           href="#" role="button">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<div class="search-overlay">
    <div class="float-default search">
        <div class="search_bg"></div>
        <div class="right-icon search_box">
            <div class="search-div">
                <input type="text" class="search_input">
                <label class="search-input-label">
                    <span class="input-label-title">Search</span>
                </label>
            </div>
            <div class="divider50"></div>
            <div class="search-result">
                <div class="search-item">
                    <div class="search-image pull-left">
                        <img src="/themes/assets/global/image/img_640x450.png" alt="search-image">
                    </div>
                    <div class="search-info pull-right">
                        <h3 class="title">Search here</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse faucibus diam quis arcu lobortis sagittis. Etiam eu ornare mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
                    </div>
                </div>
                <div class="divider15"></div>
                <div class="search-item">
                    <div class="search-info search-full pull-right">
                        <h3 class="title">Admin templates</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse faucibus diam quis arcu lobortis sagittis. Etiam eu ornare mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
                    </div>
                </div>
                <div class="divider15"></div>
                <div class="search-item">
                    <div class="search-image pull-left">
                        <img src="/themes/assets/global/image/img_640x450.png" alt="search-image">
                    </div>
                    <div class="search-info pull-right">
                        <h3 class="title">Books</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse faucibus diam quis arcu lobortis sagittis. Etiam eu ornare mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="search_close icon_close"></div>
    </div>
</div>-->
<!-- END HEADER -->
