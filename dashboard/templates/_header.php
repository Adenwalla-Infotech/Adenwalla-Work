<?php 
$_userid =  $_SESSION['userId'];
?>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo mr-5" style="margin-left:30px" href=""><img src="../uploads/images/<?php echo _siteconfig('_sitelogo'); ?>" class="mr-2" alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../uploads/images/<?php echo _siteconfig('_sitereslogo'); ?>" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    <!-- <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="icon-search"></i>
                </span>
              </div>
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul> -->
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown" style="margin-right: -30px;">
              <!-- <img src="../assets/icons/customer-service.png" style="width: 30px;">&nbsp; -->
              <!-- <i class="mdi mdi-wallet mx-0" style="color: #4B49AC;font-size: 25px;padding-top:-3px"></i>&nbsp; -->
              <img src="../assets/icons/wallet.png" style="width: 30px;padding-top:-3px">&nbsp;
              <span style="font-size: 15px;margin-right:20px;color:black;margin-bottom:-7px"><?php if(_getsingleuser($_userid, '_userwallet') == ''){
                echo 0;
              }else{ ?>
                <span id="wallet"></span>
              <?php } ?></span>
              <!-- <span class="count"></span> -->
            </a> 
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
          <form action="payment?&prod=recharge" method="get">
            <div class="row">
              <div class="col-8">
                <input type="number" min="10" class="form-control" style="height:30px;width:90%;margin-left:8px" name="amount">
              </div>
              <input type="text" hidden value="recharge" name="prod">
              <input type="text" hidden value="1" name="id">
              <div class="col-4">
                <button type="submit" style="margin-left: -25px;" class="btn btn-sm btn-primary">Add</button>
              </div>
            </div>
          </form>
              <!-- <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Application Error</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Just now
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-warning">
                    <i class="ti-settings mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Settings</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Private message
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="ti-user mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">New user registration</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    2 days ago
                  </p>
                </div>
              </a> -->
            </div>
      </li>
      <li class="nav-item nav-profile d-none d-lg-flex">
        <!-- <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
          <img src="../uploads/profile/<?php echo _getsingleuser($_userid, '_userdp'); ?>" alt="profile" />
        </a> -->
        <!-- <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="edit-profile">
            <i class="ti-settings text-primary"></i>
            My Profile
          </a>
          <a class="dropdown-item" href="logout">
            <i class="ti-power-off text-primary"></i>
            Logout
          </a>
          
        </div> -->
      </li>
      <li class="nav-item nav-profile nav-settings d-none d-lg-flex">
        <a class="nav-link" href="#" style="margin-top: -6px;">
          <img src="../uploads/profile/<?php echo _getsingleuser($_userid, '_userdp'); ?>" alt="profile" />
        </a>
      </li>
      <div id="right-sidebar" class="settings-panel">
        <i class="settings-close ti-close"></i>
        <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
          <!-- <li class="nav-item">
            <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">😃 &nbsp;&nbsp;<?php echo _getsingleuser($_userid, '_username'); ?></a>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
          </li> -->
        </ul>
        <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <!-- <div class="add-items d-flex px-3 mb-0">
              <form class="form w-100">
                <div class="form-group d-flex">
                  <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                  <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                </div>
              </form>
            </div>
            <div class="list-wrapper px-3">
              <ul class="d-flex flex-column-reverse todo-list">
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Team review meeting at 3.00 PM
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Prepare for presentation
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Resolve all the low priority tickets due today
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Schedule meeting for next week
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Project review
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
              </ul>
            </div>
            <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary mr-2"></i>
                <span>Feb 11 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
              <p class="text-gray mb-0">The total number of sessions</p>
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary mr-2"></i>
                <span>Feb 7 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
              <p class="text-gray mb-0 ">Call Sarah Graves</p>
            </div> -->
              <li class="nav-item" style="margin-top: -10px;">
                <a class="nav-link" href="index">
                  <img src="../assets/icons/speedometer.png" class="menu-icon" style="margin-right: 12px;margin-top:-5px;width:22px;margin-left:0px">
                  <span class="menu-title">Dashboard</span>
                </a>
              </li>
              <hr style="width: 100%;height:0.1px;background-color:#E5E5E5;margin-top:10px;margin-bottom:10px">
              <li class="nav-item">
                <a class="nav-link" href="myinvoice">
                  <img src="../assets/icons/validating-ticket.png" class="menu-icon" style="margin-right: 12px;margin-top:-5px;width:25px;margin-left:0px">
                  <span class="menu-title">My Invoices</span>
                </a>
              </li>
              <hr style="width: 100%;height:0.01px;background-color:#E5E5E5;margin-top:10px;margin-bottom:10px">
              <li class="nav-item">
                <a class="nav-link" href="mytranscations">
                  <!-- <i class="mdi mdi-wallet-membership menu-icon"></i> -->
                  <img src="../assets/icons/cash-flow.png" class="menu-icon" style="margin-right: 10px;margin-top:-5px;width:25px"> 
                  <span class="menu-title">My Payments</span>
                </a>
              </li>
              <hr style="width: 100%;height:0.1px;background-color:#E5E5E5;margin-top:10px;margin-bottom:10px">
              <li class="nav-item">
                <a class="nav-link" href="myexports">
                  <!-- <i class="mdi mdi-wallet-membership menu-icon"></i> -->
                  <img src="../assets/icons/share.png" class="menu-icon" style="margin-right: 10px;margin-top:-5px;width:25px"> 
                  <span class="menu-title">My Exports</span>
                </a>
              </li>
              <hr style="width: 100%;height:0.1px;background-color:#E5E5E5">
              <li class="nav-item">
                <a class="nav-link" href="edit-profile">
                  <img src="../assets/icons/settings.png" class="menu-icon" style="margin-right: 10px;margin-top:-5px;width:25px"> 
                  <span class="menu-title">My Setting</span>
                </a>
              </li>
              <hr style="width: 100%;height:0.1px;background-color:#E5E5E5;margin-top:10px;margin-bottom:10px">
              <li class="nav-item">
                <a class="nav-link" href="logout">
                  <img src="../assets/icons/shutdown.png" class="menu-icon" style="margin-right: 10px;margin-top:-5px;width:25px"> 
                  <span class="menu-title">Sign Out</span>
                </a>
              </li>
              <hr style="width: 100%;height:0.1px;background-color:#E5E5E5;margin-top:10px;margin-bottom:10px">
          </div>
          <!-- To do section tab ends -->
          <!-- <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
            <div class="d-flex align-items-center justify-content-between border-bottom">
              <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
              <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 font-weight-normal">See All</small>
            </div>
            <ul class="chat-list">
              <li class="list active">
                <div class="profile"><img src="images/faces/face1.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Thomas Douglas</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">19 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <div class="wrapper d-flex">
                    <p>Catherine</p>
                  </div>
                  <p>Away</p>
                </div>
                <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                <small class="text-muted my-auto">23 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face3.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Daniel Russell</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">14 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <p>James Richardson</p>
                  <p>Away</p>
                </div>
                <small class="text-muted my-auto">2 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face5.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Madeline Kennedy</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">5 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face6.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Sarah Graves</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">47 min</small>
              </li>
            </ul>
          </div> -->
          <!-- chat tab ends -->
        </div>
      </div>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script>
  function getData() {
    $.ajax({
      url: '_getuser.php',
      type: 'post',
      data: { "param": "_userwallet" },
      success: function (response) {
        var amount = parseFloat(response).toFixed(2).replace(/[.,]00$/, "");
        document.getElementById('wallet').innerHTML = amount;
      }
    });
  }
  setInterval(function () {
    getData();
  }, 1000)
</script>