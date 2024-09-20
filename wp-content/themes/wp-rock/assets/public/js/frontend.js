/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/components/accordion.ts":
/*!****************************************!*\
  !*** ./src/js/components/accordion.ts ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initInnerAccordion: function() { return /* binding */ initInnerAccordion; }
/* harmony export */ });
var initAccordion = function initAccordion() {
  var accordions = document.querySelectorAll('.js-wrock-accordion');
  accordions && accordions.forEach(function (item) {
    item.addEventListener('click', function (event) {
      var target = event.target;
      var btn = target.closest('.js-wrock-accordion__btn');
      if (!btn) return;
      var element = btn.parentElement;
      var content = element.querySelector('.js-wrock-accordion__content');
      var openItem = item.querySelector('.js-wrock-accordion__item.open');
      element.classList.toggle('open');
    });
  });
};
var initInnerAccordion = function initInnerAccordion() {
  var innerAccordions = document.querySelectorAll('.js-inner-accordion');
  innerAccordions && innerAccordions.forEach(function (item) {
    item.addEventListener('click', function (event) {
      var target = event.target;
      var btn = target.closest('.js-inner-accordion__btn');
      if (!btn) return;
      var element = btn.parentElement;
      var content = element.querySelector('.js-inner-accordion__content');
      var openItem = item.querySelector('.js-inner-accordion__item.open');
      element.classList.toggle('open');
    });
  });
};
/* harmony default export */ __webpack_exports__["default"] = (initAccordion);

/***/ }),

/***/ "./src/js/components/menuActions.ts":
/*!******************************************!*\
  !*** ./src/js/components/menuActions.ts ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var hoverClickEvent = function hoverClickEvent() {
  var menuItems = document.querySelectorAll('ul > .menu-item');
  var canHover = window.matchMedia('(hover: hover)').matches;
  if (canHover) {
    menuItems.forEach(function (item) {
      var subMenu = item.querySelector('.sub-menu');
      if (subMenu) {
        var bounding = subMenu.getBoundingClientRect();
        var offset = 10;
        if (bounding.right > window.innerWidth) {
          var overflow = bounding.right - window.innerWidth;
          subMenu.style.left = "-".concat(overflow + offset, "px");
        } else {
          subMenu.style.left = '0px';
        }
      }
      item.addEventListener('mouseenter', function () {
        item.classList.add('hovered');
      });
      item.addEventListener('mouseleave', function () {
        item.classList.remove('hovered');
      });
    });
  } else {
    menuItems.forEach(function (item) {
      item.addEventListener('click', function (e) {
        e.stopImmediatePropagation();
        var subMenu = item.querySelector('.sub-menu');
        if (item.classList.contains('hovered')) {
          item.classList.remove('hovered');
        } else {
          item.classList.add('hovered');
        }
        if (subMenu) {
          var bounding = subMenu.getBoundingClientRect();
          var offset = 20;
          subMenu.style.left = '50%';
          if (bounding.right > window.innerWidth) {
            var overflow = bounding.right - window.innerWidth;
            subMenu.style.left = "-".concat(overflow + offset, "px");
          }
          subMenu.addEventListener('click', function (event) {
            event.stopPropagation();
          });
        }
      });
    });
  }
};
/* harmony default export */ __webpack_exports__["default"] = (hoverClickEvent);

/***/ }),

/***/ "./src/js/components/profileFunctionality.ts":
/*!***************************************************!*\
  !*** ./src/js/components/profileFunctionality.ts ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ ProfileFunctionality; }
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var ProfileFunctionality = /*#__PURE__*/function () {
  function ProfileFunctionality() {
    _classCallCheck(this, ProfileFunctionality);
    this.profileData = {
      userId: 0,
      courses: {
        programmType: 'courses',
        programms: {}
      },
      lectures: {
        programmType: 'lectures',
        programms: {}
      }
    };
    this.initedPlayer = null;
  }
  _createClass(ProfileFunctionality, [{
    key: "init",
    value: function init() {
      this.playVideoByClickInit();
      this.createProfileVideoData('courses');
      this.createProfileVideoData('lectures');
      this.playNextVideo();
      this.editFormFieldAddEvent();
      this.addEventfetchUserDataForm();
      this.initPlayerOnOpenBlock();
    }
  }, {
    key: "loadDataAndPlayVideo",
    value: function loadDataAndPlayVideo(playBtnData) {
      var _a, _b, _c, _d, _e;
      if (!playBtnData) return;
      var containerId = (_a = playBtnData.dataset) === null || _a === void 0 ? void 0 : _a.video_container_id;
      var videoTitle = (_b = playBtnData.dataset) === null || _b === void 0 ? void 0 : _b.video_title;
      var videoId = (_c = playBtnData.dataset) === null || _c === void 0 ? void 0 : _c.video_id;
      var videoPauseTime = (_e = parseFloat((_d = playBtnData.dataset) === null || _d === void 0 ? void 0 : _d.video_pause_time)) !== null && _e !== void 0 ? _e : null;
      var videoContainer = document.querySelector("[data-video_container_id=\"".concat(containerId, "\"]"));
      var blockVideoWrapper = videoContainer.closest('.js-block-video');
      var videoTitleContainer = blockVideoWrapper === null || blockVideoWrapper === void 0 ? void 0 : blockVideoWrapper.querySelector('.js-video-title');
      if (videoTitleContainer) {
        videoTitleContainer.innerText = "".concat(videoTitle);
      }
      if (!videoContainer) return;
      videoContainer.dataset.video_id = playBtnData.dataset.video_id;
      this.loadPDFButtons(playBtnData);
      var onPauseCallback = function onPauseCallback(pauseInfo) {
        playBtnData.dataset.video_pause_time = pauseInfo.seconds;
      };
      this.initVimeoPlayer(this.initedPlayer, videoId, true, videoPauseTime, onPauseCallback);
    }
  }, {
    key: "loadPDFButtons",
    value: function loadPDFButtons(playBtnData) {
      if (!playBtnData || !playBtnData.dataset || !playBtnData.dataset.video_files) {
        console.error('Video files data is missing.');
        return;
      }
      var videoFiles = JSON.parse(playBtnData.dataset.video_files);
      var parentBlock = playBtnData.closest('.js-programm-block');
      var html = '';
      videoFiles && videoFiles.forEach(function (item) {
        if (!item.file && !item.file) return;
        html += "<a target=\"_blank\" href=\"".concat(item.file, "\" class=\"green-transparent\">\n                        <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n                            <path d=\"M7.06973 15.441C7.06973 15.0424 6.79316 14.8047 6.30519 14.8047C6.10592 14.8047 5.97098 14.8244 5.90039 14.8432V16.122C5.98397 16.1408 6.08671 16.1471 6.22833 16.1471C6.74846 16.1471 7.06973 15.8843 7.06973 15.441Z\" fill=\"white\"/>\n                            <path d=\"M10.0907 14.8179C9.87221 14.8179 9.73099 14.8367 9.64746 14.8563V17.6885C9.73104 17.7081 9.86599 17.7081 9.98799 17.7081C10.8745 17.7144 11.4527 17.2264 11.4527 16.1924C11.4589 15.2933 10.9321 14.8179 10.0907 14.8179Z\" fill=\"white\"/>\n                            <path d=\"M15.703 0H6.06363C4.65541 0 3.50927 1.14694 3.50927 2.55436V12H3.25978C2.69141 12 2.23047 12.4604 2.23047 13.0293V19.2717C2.23047 19.8405 2.69136 20.3009 3.25978 20.3009H3.50927V21.4456C3.50927 22.8546 4.65541 24 6.06363 24H19.2161C20.6235 24 21.7698 22.8545 21.7698 21.4456V6.0455L15.703 0ZM4.93078 14.1558C5.23243 14.1049 5.65644 14.0664 6.25383 14.0664C6.85754 14.0664 7.28782 14.1817 7.57693 14.4131C7.8531 14.6313 8.03947 14.9913 8.03947 15.415C8.03947 15.8386 7.89825 16.1988 7.6413 16.4427C7.30709 16.7573 6.81284 16.8986 6.23467 16.8986C6.10599 16.8986 5.99065 16.8922 5.90046 16.8797V18.4276H4.93078V14.1558ZM19.2161 22.4356H6.06363C5.51836 22.4356 5.07434 21.9916 5.07434 21.4456V20.3009H17.3352C17.9036 20.3009 18.3645 19.8405 18.3645 19.2717V13.0293C18.3645 12.4604 17.9036 12 17.3352 12H5.07434V2.55436C5.07434 2.00989 5.51841 1.56587 6.06363 1.56587L15.1178 1.55641V4.90314C15.1178 5.88068 15.9109 6.67459 16.8892 6.67459L20.1677 6.66518L20.2046 21.4455C20.2046 21.9916 19.7614 22.4356 19.2161 22.4356ZM8.66473 18.408V14.1558C9.02438 14.0986 9.49314 14.0664 9.98783 14.0664C10.81 14.0664 11.3431 14.2139 11.7608 14.5285C12.2104 14.8627 12.4928 15.3954 12.4928 16.1603C12.4928 16.9887 12.1911 17.5607 11.7733 17.9136C11.3175 18.2925 10.6236 18.4722 9.77598 18.4722C9.26834 18.4722 8.90869 18.4401 8.66473 18.408ZM15.6748 15.8905V16.6867H14.1203V18.4276H13.1376V14.0986H15.7838V14.9011H14.1203V15.8905H15.6748Z\" fill=\"white\"/>\n                        </svg>\n                        ").concat(item.file_name, "\n                    </a>");
      });
      if (parentBlock) {
        var videoembedFiles = parentBlock.querySelector('.js-video-embed-files');
        videoembedFiles.innerHTML = html;
      }
    }
  }, {
    key: "playVideoByClickInit",
    value: function playVideoByClickInit() {
      var _this = this;
      var playVideoBtns = document.querySelectorAll('.js-play-video-btn');
      if (!playVideoBtns) return;
      var removeAllActiveBtns = function removeAllActiveBtns() {
        playVideoBtns.forEach(function (el) {
          return el.classList.remove('playing-video');
        });
      };
      playVideoBtns.forEach(function (el) {
        var button = el;
        button.addEventListener('click', function (e) {
          e.stopPropagation();
          removeAllActiveBtns();
          button.classList.add('playing-video');
          _this.loadDataAndPlayVideo(button);
        });
      });
    }
  }, {
    key: "saveVideoTimeData",
    value: function saveVideoTimeData(videoContainer, videoParams, learninMaterialType) {
      var _a, _b;
      if (!videoContainer && !videoParams && !learninMaterialType) return;
      var videoDuration = videoParams.duration;
      var videoPauseTime = videoParams.seconds;
      var programmId = (_a = videoContainer.dataset.video_container_id) === null || _a === void 0 ? void 0 : _a.split('_')[0];
      var shortBlockId = (_b = videoContainer.dataset.video_container_id) === null || _b === void 0 ? void 0 : _b.split('_')[1];
      var videoId = videoContainer.dataset.video_id;
      var viewed = false;
      if (videoParams.percent) {
        viewed = videoParams.percent >= 0.9;
      }
      var currentProgrammPath = this.profileData[learninMaterialType].programms[programmId];
      var currentBlockPath = currentProgrammPath === null || currentProgrammPath === void 0 ? void 0 : currentProgrammPath.blocks[shortBlockId];
      currentBlockPath.fullBlockId = videoContainer.dataset.video_container_id;
      currentBlockPath.videos[videoId] = Object.assign(Object.assign({}, currentBlockPath.videos[videoId]), {
        videoDuration: videoDuration,
        videoPauseTime: videoPauseTime,
        isVideoViewed: viewed
      });
      this.changeBlockStatus(currentBlockPath);
      this.changePassedBlocksCount(currentProgrammPath);
      this.fetchDataToBackend(this.profileData);
      this.visualUpdateProgressBar(videoContainer, learninMaterialType);
    }
  }, {
    key: "createProfileVideoData",
    value: function createProfileVideoData(learninMaterialType) {
      var _this2 = this;
      var mainContainer = document.querySelector("#".concat(learninMaterialType));
      if (!mainContainer) return;
      var playVideoBtns = mainContainer.querySelectorAll('.js-play-video-btn');
      if (mainContainer && mainContainer.dataset.user_id) {
        this.profileData.userId = +mainContainer.dataset.user_id;
      }
      playVideoBtns && playVideoBtns.forEach(function (el) {
        var _a, _b, _c, _d, _e, _f, _g, _h, _j, _k;
        var button = el;
        var programmId = (_b = (_a = button.dataset) === null || _a === void 0 ? void 0 : _a.video_container_id) === null || _b === void 0 ? void 0 : _b.split('_')[0];
        var blockId = (_d = (_c = button.dataset) === null || _c === void 0 ? void 0 : _c.video_container_id) === null || _d === void 0 ? void 0 : _d.split('_')[1];
        var videoId = (_e = button.dataset) === null || _e === void 0 ? void 0 : _e.video_id;
        var videoDuration = (_f = button.dataset) === null || _f === void 0 ? void 0 : _f.video_duration;
        var videoPauseTime = (_g = button.dataset) === null || _g === void 0 ? void 0 : _g.video_pause_time;
        var videoIsViewed = (_h = button.dataset) === null || _h === void 0 ? void 0 : _h.video_viewed;
        var blocksPassedCount = (_j = button.dataset) === null || _j === void 0 ? void 0 : _j.passed_blocks_count;
        if (!programmId || !blockId || !videoId) return;
        if (!_this2.profileData[learninMaterialType].programms[programmId]) {
          _this2.profileData[learninMaterialType].programms[programmId] = {
            programmId: +programmId.split('-')[1] || null,
            blocksPassed: blocksPassedCount,
            blocks: {}
          };
        }
        if (!_this2.profileData[learninMaterialType].programms[programmId].blocks[blockId]) {
          var currentBlock = _this2.getCurrentBlock(programmId, blockId);
          _this2.profileData[learninMaterialType].programms[programmId].blocks[blockId] = {
            blockStatus: ((_k = currentBlock === null || currentBlock === void 0 ? void 0 : currentBlock.dataset) === null || _k === void 0 ? void 0 : _k.block_status) || null,
            videos: {}
          };
        }
        var currentProgrammPath = _this2.profileData[learninMaterialType].programms[programmId];
        var currentBlockPath = currentProgrammPath === null || currentProgrammPath === void 0 ? void 0 : currentProgrammPath.blocks[blockId];
        currentBlockPath.videos[videoId] = {
          videoId: videoId || null,
          videoDuration: videoDuration || null,
          videoPauseTime: videoPauseTime || null,
          isVideoViewed: videoIsViewed || ''
        };
      });
    }
  }, {
    key: "changeBlockStatus",
    value: function changeBlockStatus(currentBlockObject) {
      if (!currentBlockObject) return;
      var status = '';
      var videosArray = Object.values(currentBlockObject.videos);
      var isBlockPassed = videosArray.every(function (video) {
        return video.isVideoViewed;
      });
      var isBlockNotPassed = videosArray.every(function (video) {
        return !video.isVideoViewed;
      });
      if (isBlockPassed) {
        status = 'passed';
      } else if (isBlockNotPassed) {
        status = 'not-passed';
      } else {
        status = 'in-progress';
      }
      currentBlockObject.blockStatus = status;
      this.visualUpdateBlockStatus(currentBlockObject.fullBlockId, status);
    }
  }, {
    key: "visualUpdateBlockStatus",
    value: function visualUpdateBlockStatus(blockContainerId, status) {
      var queryStr = ".js-programm-block[data-block_id=\"".concat(blockContainerId, "\"]");
      var blockElem = document.querySelector(queryStr);
      if (blockElem) {
        blockElem.dataset.block_status = status;
        var statusContainer = blockElem.querySelector('.js-block-status');
        if (statusContainer) {
          statusContainer.classList.remove('passed');
          statusContainer.classList.remove('not-passed');
          statusContainer.classList.remove('in-progress');
          statusContainer.classList.add(status);
        }
      }
    }
  }, {
    key: "visualUpdateProgressBar",
    value: function visualUpdateProgressBar(videoContainer, learninMaterialType) {
      var programmIdStr = videoContainer === null || videoContainer === void 0 ? void 0 : videoContainer.dataset.video_container_id.split('_')[0];
      var programmItem = document.querySelector("[data-programm_id=\"".concat(programmIdStr, "\"]"));
      var progressBarWrap = programmItem.querySelector('.js-progress-info');
      if (!this.profileData[learninMaterialType].programms) return;
      var programmsData = this.profileData[learninMaterialType].programms;
      if (!programmsData[programmIdStr].blocks) return;
      var blocksData = programmsData[programmIdStr].blocks;
      var blocksCount = Object.keys(blocksData).length;
      var passedBlocksCount = programmsData[programmIdStr].blocksPassed;
      var passedBlocksSpan = progressBarWrap.querySelector('.js-passed-blocks-span');
      var progressBar = progressBarWrap.querySelector('.js-progress-bar');
      if (progressBar) {
        progressBar.innerHTML = this.generateProgressBar(161, blocksCount, passedBlocksCount);
      }
      if (passedBlocksSpan) {
        passedBlocksSpan.innerText = "".concat(passedBlocksCount);
      }
    }
  }, {
    key: "generateProgressBar",
    value: function generateProgressBar(totalWidth, blocksCount, blocksPassed) {
      var blockWidth = 0;
      if (blocksCount !== 0) {
        blockWidth = totalWidth / blocksCount;
      }
      var svg = '';
      svg += "<rect width=\"".concat(totalWidth, "\" height=\"5\" transform=\"matrix(1 0 0 -1 0 5)\" fill=\"#131614\" />");
      for (var i = 0; i < blocksPassed; i++) {
        var xPosition = i * blockWidth;
        svg += "<rect width=\"".concat(blockWidth, "\" height=\"5\" transform=\"matrix(1 0 0 -1 ").concat(xPosition, " 5)\" fill=\"#53F07F\" />");
      }
      return svg;
    }
  }, {
    key: "changePassedBlocksCount",
    value: function changePassedBlocksCount(currentProgrammObject) {
      if (!currentProgrammObject) return;
      var blocksArray = Object.values(currentProgrammObject.blocks);
      var countOfPassedBlocks = blocksArray.filter(function (block) {
        return block.blockStatus === 'passed';
      });
      currentProgrammObject.blocksPassed = countOfPassedBlocks.length;
    }
  }, {
    key: "playNextVideo",
    value: function playNextVideo() {
      var _this3 = this;
      var playNextBtns = document.querySelectorAll('.js-next-video-btn');
      playNextBtns && playNextBtns.forEach(function (el) {
        var btn = el;
        btn.addEventListener('click', function (e) {
          e.stopPropagation();
          var videoBlock = btn.closest('.js-programm-block');
          var playVideoBtns = videoBlock.querySelectorAll('.js-play-video-btn');
          var playVideoBtn = videoBlock.querySelector('.js-play-video-btn.playing-video');
          var nextPLayBtn = playVideoBtn === null || playVideoBtn === void 0 ? void 0 : playVideoBtn.nextSibling;
          if (nextPLayBtn) {
            playVideoBtns && playVideoBtns.forEach(function (el2) {
              var btn2 = el2;
              btn2.classList.remove('playing-video');
            });
            nextPLayBtn.classList.add('playing-video');
            _this3.loadDataAndPlayVideo(nextPLayBtn);
          }
        });
      });
    }
  }, {
    key: "fetchDataToBackend",
    value: function fetchDataToBackend(profileDataParams) {
      if (!profileDataParams) return;
      fetch("".concat(var_from_php.ajax_url, "?action=save_video_data"), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(profileDataParams)
      });
    }
  }, {
    key: "getCurrentBlock",
    value: function getCurrentBlock(programmId, blockId) {
      var blockIdstring = "".concat(programmId, "_").concat(blockId);
      var currentBlock = document.querySelector("[data-block_id=\"".concat(blockIdstring, "\"]"));
      return currentBlock || null;
    }
  }, {
    key: "addEventfetchUserDataForm",
    value: function addEventfetchUserDataForm() {
      var form = document.querySelector('.js-user-info-form');
      if (!form) return;
      form && form.addEventListener('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(form);
        fetch("".concat(var_from_php.ajax_url, "?action=update_user_data"), {
          method: 'POST',
          body: formData
        }).then(function (res) {
          return res.json();
        }).then(function (res) {
          var respContainer = form.querySelector('.js-response-container');
          var additionalClass = res.success ? 'success' : 'error';
          if (respContainer) {
            var paragrahp = document.createElement('p');
            paragrahp.classList.add(additionalClass);
            paragrahp.innerText = res.data;
            respContainer.appendChild(paragrahp);
          }
        });
      });
    }
  }, {
    key: "editFormFieldAddEvent",
    value: function editFormFieldAddEvent() {
      var editBtns = document.querySelectorAll('.js-edit-btn');
      var inpus = document.querySelectorAll('input[type="text"]');
      var setInputDefaultState = function setInputDefaultState(el) {
        var input = el;
        input.classList.remove('focus');
      };
      editBtns && editBtns.forEach(function (el) {
        var btn = el;
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          inpus && inpus.forEach(function (el2) {
            return setInputDefaultState(el2);
          });
          var parentWrapper = btn.closest('.js-inner-input-wrapper');
          var input = parentWrapper === null || parentWrapper === void 0 ? void 0 : parentWrapper.querySelector('input[type="text"]');
          input.classList.add('focus');
        });
      });
    }
  }, {
    key: "initVimeoPlayer",
    value: function initVimeoPlayer(playerEl, videoId) {
      var loadVideo = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
      var start = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0;
      var cb = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (pauseInfo) {};
      var player;
      if (!loadVideo) {
        player = new Vimeo.Player(playerEl, {
          id: videoId
        });
        start && player.setCurrentTime(start);
      } else {
        player = playerEl;
        player.loadVideo(videoId).then(function () {
          start && player.setCurrentTime(start);
        });
      }
      player.off('pause', cb);
      player.on('pause', cb);
      return player;
    }
  }, {
    key: "initPlayerOnOpenBlock",
    value: function initPlayerOnOpenBlock() {
      var _this4 = this;
      var blocks = document.querySelectorAll('.js-programm-block');
      blocks && blocks.forEach(function (el) {
        var block = el;
        block.addEventListener('click', function () {
          var player = block.querySelector("[data-video_container_id=\"".concat(block.dataset.block_id, "\"]"));
          var firstPlayBtnInBlcok = block.querySelector('.js-play-video-btn');
          var videoStartTime = (firstPlayBtnInBlcok === null || firstPlayBtnInBlcok === void 0 ? void 0 : firstPlayBtnInBlcok.dataset.video_pause_time) ? +firstPlayBtnInBlcok.dataset.video_pause_time : 0;
          if (!player || !firstPlayBtnInBlcok) return;
          firstPlayBtnInBlcok.classList.add('playing-video');
          var onPauseCallback = function onPauseCallback(pauseInfo) {
            var parentProgramm = block.closest('.js-programm');
            var playVideoBtn = block.querySelector("#video-btn-".concat(player.dataset.video_id));
            if (playVideoBtn) {
              playVideoBtn.dataset.video_pause_time = "".concat(pauseInfo.seconds);
            }
            _this4.saveVideoTimeData(player, pauseInfo, parentProgramm.id);
          };
          _this4.initedPlayer = _this4.initVimeoPlayer(player, player.dataset.video_id, false, videoStartTime, onPauseCallback);
        });
      });
    }
  }]);
  return ProfileFunctionality;
}();


/***/ }),

/***/ "./src/scss/frontend.scss":
/*!********************************!*\
  !*** ./src/scss/frontend.scss ***!
  \********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./node_modules/smoothscroll-polyfill/dist/smoothscroll.js":
/*!*****************************************************************!*\
  !*** ./node_modules/smoothscroll-polyfill/dist/smoothscroll.js ***!
  \*****************************************************************/
/***/ (function(module) {

/* smoothscroll v0.4.4 - 2019 - Dustan Kasten, Jeremias Menichelli - MIT License */
(function () {
  'use strict';

  // polyfill
  function polyfill() {
    // aliases
    var w = window;
    var d = document;

    // return if scroll behavior is supported and polyfill is not forced
    if (
      'scrollBehavior' in d.documentElement.style &&
      w.__forceSmoothScrollPolyfill__ !== true
    ) {
      return;
    }

    // globals
    var Element = w.HTMLElement || w.Element;
    var SCROLL_TIME = 468;

    // object gathering original scroll methods
    var original = {
      scroll: w.scroll || w.scrollTo,
      scrollBy: w.scrollBy,
      elementScroll: Element.prototype.scroll || scrollElement,
      scrollIntoView: Element.prototype.scrollIntoView
    };

    // define timing method
    var now =
      w.performance && w.performance.now
        ? w.performance.now.bind(w.performance)
        : Date.now;

    /**
     * indicates if a the current browser is made by Microsoft
     * @method isMicrosoftBrowser
     * @param {String} userAgent
     * @returns {Boolean}
     */
    function isMicrosoftBrowser(userAgent) {
      var userAgentPatterns = ['MSIE ', 'Trident/', 'Edge/'];

      return new RegExp(userAgentPatterns.join('|')).test(userAgent);
    }

    /*
     * IE has rounding bug rounding down clientHeight and clientWidth and
     * rounding up scrollHeight and scrollWidth causing false positives
     * on hasScrollableSpace
     */
    var ROUNDING_TOLERANCE = isMicrosoftBrowser(w.navigator.userAgent) ? 1 : 0;

    /**
     * changes scroll position inside an element
     * @method scrollElement
     * @param {Number} x
     * @param {Number} y
     * @returns {undefined}
     */
    function scrollElement(x, y) {
      this.scrollLeft = x;
      this.scrollTop = y;
    }

    /**
     * returns result of applying ease math function to a number
     * @method ease
     * @param {Number} k
     * @returns {Number}
     */
    function ease(k) {
      return 0.5 * (1 - Math.cos(Math.PI * k));
    }

    /**
     * indicates if a smooth behavior should be applied
     * @method shouldBailOut
     * @param {Number|Object} firstArg
     * @returns {Boolean}
     */
    function shouldBailOut(firstArg) {
      if (
        firstArg === null ||
        typeof firstArg !== 'object' ||
        firstArg.behavior === undefined ||
        firstArg.behavior === 'auto' ||
        firstArg.behavior === 'instant'
      ) {
        // first argument is not an object/null
        // or behavior is auto, instant or undefined
        return true;
      }

      if (typeof firstArg === 'object' && firstArg.behavior === 'smooth') {
        // first argument is an object and behavior is smooth
        return false;
      }

      // throw error when behavior is not supported
      throw new TypeError(
        'behavior member of ScrollOptions ' +
          firstArg.behavior +
          ' is not a valid value for enumeration ScrollBehavior.'
      );
    }

    /**
     * indicates if an element has scrollable space in the provided axis
     * @method hasScrollableSpace
     * @param {Node} el
     * @param {String} axis
     * @returns {Boolean}
     */
    function hasScrollableSpace(el, axis) {
      if (axis === 'Y') {
        return el.clientHeight + ROUNDING_TOLERANCE < el.scrollHeight;
      }

      if (axis === 'X') {
        return el.clientWidth + ROUNDING_TOLERANCE < el.scrollWidth;
      }
    }

    /**
     * indicates if an element has a scrollable overflow property in the axis
     * @method canOverflow
     * @param {Node} el
     * @param {String} axis
     * @returns {Boolean}
     */
    function canOverflow(el, axis) {
      var overflowValue = w.getComputedStyle(el, null)['overflow' + axis];

      return overflowValue === 'auto' || overflowValue === 'scroll';
    }

    /**
     * indicates if an element can be scrolled in either axis
     * @method isScrollable
     * @param {Node} el
     * @param {String} axis
     * @returns {Boolean}
     */
    function isScrollable(el) {
      var isScrollableY = hasScrollableSpace(el, 'Y') && canOverflow(el, 'Y');
      var isScrollableX = hasScrollableSpace(el, 'X') && canOverflow(el, 'X');

      return isScrollableY || isScrollableX;
    }

    /**
     * finds scrollable parent of an element
     * @method findScrollableParent
     * @param {Node} el
     * @returns {Node} el
     */
    function findScrollableParent(el) {
      while (el !== d.body && isScrollable(el) === false) {
        el = el.parentNode || el.host;
      }

      return el;
    }

    /**
     * self invoked function that, given a context, steps through scrolling
     * @method step
     * @param {Object} context
     * @returns {undefined}
     */
    function step(context) {
      var time = now();
      var value;
      var currentX;
      var currentY;
      var elapsed = (time - context.startTime) / SCROLL_TIME;

      // avoid elapsed times higher than one
      elapsed = elapsed > 1 ? 1 : elapsed;

      // apply easing to elapsed time
      value = ease(elapsed);

      currentX = context.startX + (context.x - context.startX) * value;
      currentY = context.startY + (context.y - context.startY) * value;

      context.method.call(context.scrollable, currentX, currentY);

      // scroll more if we have not reached our destination
      if (currentX !== context.x || currentY !== context.y) {
        w.requestAnimationFrame(step.bind(w, context));
      }
    }

    /**
     * scrolls window or element with a smooth behavior
     * @method smoothScroll
     * @param {Object|Node} el
     * @param {Number} x
     * @param {Number} y
     * @returns {undefined}
     */
    function smoothScroll(el, x, y) {
      var scrollable;
      var startX;
      var startY;
      var method;
      var startTime = now();

      // define scroll context
      if (el === d.body) {
        scrollable = w;
        startX = w.scrollX || w.pageXOffset;
        startY = w.scrollY || w.pageYOffset;
        method = original.scroll;
      } else {
        scrollable = el;
        startX = el.scrollLeft;
        startY = el.scrollTop;
        method = scrollElement;
      }

      // scroll looping over a frame
      step({
        scrollable: scrollable,
        method: method,
        startTime: startTime,
        startX: startX,
        startY: startY,
        x: x,
        y: y
      });
    }

    // ORIGINAL METHODS OVERRIDES
    // w.scroll and w.scrollTo
    w.scroll = w.scrollTo = function() {
      // avoid action when no arguments are passed
      if (arguments[0] === undefined) {
        return;
      }

      // avoid smooth behavior if not required
      if (shouldBailOut(arguments[0]) === true) {
        original.scroll.call(
          w,
          arguments[0].left !== undefined
            ? arguments[0].left
            : typeof arguments[0] !== 'object'
              ? arguments[0]
              : w.scrollX || w.pageXOffset,
          // use top prop, second argument if present or fallback to scrollY
          arguments[0].top !== undefined
            ? arguments[0].top
            : arguments[1] !== undefined
              ? arguments[1]
              : w.scrollY || w.pageYOffset
        );

        return;
      }

      // LET THE SMOOTHNESS BEGIN!
      smoothScroll.call(
        w,
        d.body,
        arguments[0].left !== undefined
          ? ~~arguments[0].left
          : w.scrollX || w.pageXOffset,
        arguments[0].top !== undefined
          ? ~~arguments[0].top
          : w.scrollY || w.pageYOffset
      );
    };

    // w.scrollBy
    w.scrollBy = function() {
      // avoid action when no arguments are passed
      if (arguments[0] === undefined) {
        return;
      }

      // avoid smooth behavior if not required
      if (shouldBailOut(arguments[0])) {
        original.scrollBy.call(
          w,
          arguments[0].left !== undefined
            ? arguments[0].left
            : typeof arguments[0] !== 'object' ? arguments[0] : 0,
          arguments[0].top !== undefined
            ? arguments[0].top
            : arguments[1] !== undefined ? arguments[1] : 0
        );

        return;
      }

      // LET THE SMOOTHNESS BEGIN!
      smoothScroll.call(
        w,
        d.body,
        ~~arguments[0].left + (w.scrollX || w.pageXOffset),
        ~~arguments[0].top + (w.scrollY || w.pageYOffset)
      );
    };

    // Element.prototype.scroll and Element.prototype.scrollTo
    Element.prototype.scroll = Element.prototype.scrollTo = function() {
      // avoid action when no arguments are passed
      if (arguments[0] === undefined) {
        return;
      }

      // avoid smooth behavior if not required
      if (shouldBailOut(arguments[0]) === true) {
        // if one number is passed, throw error to match Firefox implementation
        if (typeof arguments[0] === 'number' && arguments[1] === undefined) {
          throw new SyntaxError('Value could not be converted');
        }

        original.elementScroll.call(
          this,
          // use left prop, first number argument or fallback to scrollLeft
          arguments[0].left !== undefined
            ? ~~arguments[0].left
            : typeof arguments[0] !== 'object' ? ~~arguments[0] : this.scrollLeft,
          // use top prop, second argument or fallback to scrollTop
          arguments[0].top !== undefined
            ? ~~arguments[0].top
            : arguments[1] !== undefined ? ~~arguments[1] : this.scrollTop
        );

        return;
      }

      var left = arguments[0].left;
      var top = arguments[0].top;

      // LET THE SMOOTHNESS BEGIN!
      smoothScroll.call(
        this,
        this,
        typeof left === 'undefined' ? this.scrollLeft : ~~left,
        typeof top === 'undefined' ? this.scrollTop : ~~top
      );
    };

    // Element.prototype.scrollBy
    Element.prototype.scrollBy = function() {
      // avoid action when no arguments are passed
      if (arguments[0] === undefined) {
        return;
      }

      // avoid smooth behavior if not required
      if (shouldBailOut(arguments[0]) === true) {
        original.elementScroll.call(
          this,
          arguments[0].left !== undefined
            ? ~~arguments[0].left + this.scrollLeft
            : ~~arguments[0] + this.scrollLeft,
          arguments[0].top !== undefined
            ? ~~arguments[0].top + this.scrollTop
            : ~~arguments[1] + this.scrollTop
        );

        return;
      }

      this.scroll({
        left: ~~arguments[0].left + this.scrollLeft,
        top: ~~arguments[0].top + this.scrollTop,
        behavior: arguments[0].behavior
      });
    };

    // Element.prototype.scrollIntoView
    Element.prototype.scrollIntoView = function() {
      // avoid smooth behavior if not required
      if (shouldBailOut(arguments[0]) === true) {
        original.scrollIntoView.call(
          this,
          arguments[0] === undefined ? true : arguments[0]
        );

        return;
      }

      // LET THE SMOOTHNESS BEGIN!
      var scrollableParent = findScrollableParent(this);
      var parentRects = scrollableParent.getBoundingClientRect();
      var clientRects = this.getBoundingClientRect();

      if (scrollableParent !== d.body) {
        // reveal element inside parent
        smoothScroll.call(
          this,
          scrollableParent,
          scrollableParent.scrollLeft + clientRects.left - parentRects.left,
          scrollableParent.scrollTop + clientRects.top - parentRects.top
        );

        // reveal parent in viewport unless is fixed
        if (w.getComputedStyle(scrollableParent).position !== 'fixed') {
          w.scrollBy({
            left: parentRects.left,
            top: parentRects.top,
            behavior: 'smooth'
          });
        }
      } else {
        // reveal element in viewport
        w.scrollBy({
          left: clientRects.left,
          top: clientRects.top,
          behavior: 'smooth'
        });
      }
    };
  }

  if (true) {
    // commonjs
    module.exports = { polyfill: polyfill };
  } else {}

}());


/***/ }),

/***/ "./src/js/parts/formsActionsClass.js":
/*!*******************************************!*\
  !*** ./src/js/parts/formsActionsClass.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ FormsActionsClass; }
/* harmony export */ });
/* harmony import */ var _helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers */ "./src/js/parts/helpers.js");


class FormsActionsClass {
    constructor(popupInstance, validateField) {
        this.popupInstance = popupInstance;
        this.validateField = validateField; // function
    }

    async init() {
        this.checkFormFields();

        this.loginFormFetch();
        this.restorePasswordFormFetch();
        this.getAccessFormFetch();
        this.setNewPasswordFormFetch();
        this.buyProgrammFormFetch();
    }

    loginFormFetch() {
        const loginForm = document.querySelector('.js-login-form');
        if (!loginForm) return;

        loginForm &&
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                // @ts-ignore
                const res = await this.fetchToAction(
                    loginForm,
                    'user_login_ajax'
                );

                this.setDataToRespContainer(res, loginForm);
                if (res.success) {
                    window.location.reload();
                }
            });
    }

    checkFormFields() {
        const formInputs = document.querySelectorAll('input');
        if (!formInputs) return;
        let repeatPassword = '';

        const notValidText = (inputName) => {
            let textResult = '';

            switch (inputName) {
                case 'name':
                    textResult =
                        'Ведіть данні у форматі "First name" "Second name" або "First name"';
                    break;
                case 'email':
                    textResult = 'Невірний формат воду email';
                    break;
                case 'phone':
                    textResult =
                        'Невірний формат воду телефону "+380000000000"';
                    break;
                default:
                    textResult = 'Невірний формат воду';
            }

            return textResult;
        };

        const checkAllInputs = (target) => {
            let allInputsValid = true;

            const parentForm = target.closest('form');

            if (!parentForm) return;

            const allInnerInputs = parentForm.querySelectorAll('input');
            const formSubmit = parentForm.querySelector('input[type="submit"]');

            allInnerInputs.forEach((input) => {
                const inputContainer =
                    input.closest('.js-inner-input-wrapper') ?? null;

                if (
                    !input.name ||
                    !input.value ||
                    input.type === 'hidden' ||
                    input.name === 'password' ||
                    input.name === 'password-repeat'
                )
                    return;

                const isValid = this.validateField(input.name, input.value);

                // eslint-disable-next-line no-shadow
                const notValidTextParagraph =
                    inputContainer &&
                    inputContainer.querySelector('.js-not-valid-text');

                notValidTextParagraph && notValidTextParagraph.remove();

                if (isValid) {
                    input && input.classList.add('valid');
                    input && input.classList.remove('not-valid');
                    inputContainer && inputContainer.classList.add('valid');
                    inputContainer &&
                        inputContainer.classList.remove('not-valid');
                } else {
                    const p = document.createElement('p');
                    p.classList.add('js-not-valid-text');
                    p.classList.add('not-valid-text');
                    p.innerText = notValidText(input.name);

                    inputContainer && inputContainer.appendChild(p);

                    input && input.classList.add('not-valid');
                    input && input.classList.remove('valid');
                    inputContainer && inputContainer.classList.add('not-valid');
                    inputContainer && inputContainer.classList.remove('valid');
                    allInputsValid = false;
                }
            });
            if (formSubmit) {
                formSubmit.disabled = !allInputsValid;
            }
        };

        const checkPasswordMatch = (target) => {
            const parentForm = target.closest('form');
            if (!parentForm) return;

            if (target.name === 'password-repeat') {
                repeatPassword = target.value;

                const passwordInput = parentForm.querySelector(
                    'input[name="password"]'
                );
                if (!passwordInput || target.name === 'password') return;

                const passwordsMatch = passwordInput.value === repeatPassword;

                target.classList.toggle('valid', passwordsMatch);
                target.classList.toggle('not-valid', !passwordsMatch);

                const formSubmit = parentForm.querySelector(
                    'input[type="submit"]'
                );
                formSubmit && (formSubmit.disabled = !passwordsMatch);
            }
        };

        formInputs &&
            formInputs.forEach((input) => {
                input.addEventListener('change', (e) => {
                    checkAllInputs(e.target);
                    checkPasswordMatch(e.target);
                });
            });
    }

    restorePasswordFormFetch() {
        const forgotPasswordForm = document.querySelector(
            '.js-forgot-password-form'
        );

        if (!forgotPasswordForm) return;

        forgotPasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await this.fetchToAction(
                forgotPasswordForm,
                'forgot_password'
            );

            if (!res.success) {
                this.setDataToRespContainer(res, forgotPasswordForm);
            } else {
                this.popupInstance.openOnePopup('#forgot-password-popup');
            }
        });
    }

    getAccessFormFetch() {
        const getAccessForm = document.querySelector('.js-get-access-form');

        if (!getAccessForm) return;

        // Get access to programm: registering user and buying programm
        getAccessForm &&
            getAccessForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Set user data for regitering
                const res = await this.fetchToAction(
                    getAccessForm,
                    'register_login_user'
                );
                this.setOrderCookieInfo(e.target);
                if (res.success) {
                    const payRes = await this.fetchToActionPayload({
                        product_id: e.target['post-id'].value,
                        variation_id: 0,
                        amount: e.target.amount.value,
                        quantity: '1',
                    });
                    if (payRes) {
                        // If success return to pay page
                        window.location.href = payRes;
                    } else {
                        this.setDataToRespContainer(payRes, getAccessForm);
                    }
                } else {
                    this.setDataToRespContainer(res, getAccessForm);
                }
            });
    }

    async fetchToAction(form, actionName) {
        const formData = new FormData(form);
        const resJSON = await fetch(
            // eslint-disable-next-line no-undef
            `${var_from_php.ajax_url}?action=${actionName}`,
            {
                method: 'POST',
                body: formData,
            }
        );

        const res = await resJSON.json();
        return res;
    }

    async fetchToActionPayload(productData) {
        const bodyString = `action=mrkv_monopay_product&product=${encodeURIComponent(
            JSON.stringify(productData)
        )}`;

        const resJSON = await fetch(
            // eslint-disable-next-line no-undef
            `${var_from_php.ajax_url}`,
            {
                headers: {
                    Accept: '/',
                    'Accept-Language': 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                    'Cache-Control': 'no-cache',
                    'Content-Type':
                        'application/x-www-form-urlencoded; charset=UTF-8',
                },
                // referrer: window.location.href,
                // referrerPolicy: 'strict-origin-when-cross-origin',
                body: bodyString,
                method: 'POST', // Метод запроса (POST)
                mode: 'cors', // Режим CORS для запросов к другим доменам
                credentials: 'include',
            }
        );
        const res = await resJSON.text();
        return res;
    }

    setNewPasswordFormFetch() {
        const newPasswordForm = document.querySelector(
            '.js-set-new-password-form'
        );

        if (!newPasswordForm) return;

        newPasswordForm &&
            newPasswordForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const res = await this.fetchToAction(
                    newPasswordForm,
                    'set_new_password'
                );

                this.setDataToRespContainer(res, newPasswordForm);
            });
    }

    buyProgrammFormFetch() {
        const buyProgrammForm = document.querySelectorAll(
            '.js-buy-programm-form'
        );

        if (!buyProgrammForm) return;

        buyProgrammForm &&
            buyProgrammForm.forEach((form) => {
                // Buy programm by registered user
                form &&
                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();

                        const submitBtnWrapper =
                            e.target.querySelector('.bottom-wrapper');
                        submitBtnWrapper &&
                            submitBtnWrapper.classList.add('loading');

                        const enteredPromocode = e.target?.promocode?.value;

                        // FETCH TO MONO PAY SYSTEM ( Create pay account)
                        let periodAmount = [];
                        let amount = null;
                        if (
                            e.target?.['period-amount']?.value &&
                            !e.target?.amount?.value
                        ) {
                            periodAmount = e.target?.['period-amount']?.value
                                ? e.target['period-amount'].value.split('|')
                                : [];
                        }

                        if (periodAmount) {
                            // eslint-disable-next-line prefer-destructuring
                            amount = periodAmount[1];
                        } else {
                            amount = e.target?.amount?.value;
                        }

                        const payRes = await this.fetchToActionPayload({
                            product_id: e.target['post-id'].value,
                            variation_id: 0,
                            amount,
                            forcePrice: amount,
                            discount: enteredPromocode,
                            quantity: '1',
                        });
                        if (payRes) {
                            this.setOrderCookieInfo(form);
                            // If success return to pay page
                            window.location.href = payRes;
                        } else {
                            this.setDataToRespContainer(payRes, form);
                        }

                        submitBtnWrapper &&
                            submitBtnWrapper.classList.remove('loading');
                    });
            });
    }

    setDataToRespContainer(res, parentForm) {
        if (!parentForm || !res) return;

        const respContainer = parentForm.querySelector(
            '.js-response-container'
        );
        if (!respContainer) return;

        const additionalClass = res.success ? 'success' : 'error';
        const paragrahp = document.createElement('p');
        paragrahp.classList.add(additionalClass);
        paragrahp.innerText = res.data;
        respContainer.innerHTML = '';
        respContainer.appendChild(paragrahp);
    }

    setOrderCookieInfo(form) {
        const formData = new FormData(form);
        let data = {
            userEmail: formData.get('email'),
            userPhone: formData.get('phone'),
            userFullName: formData.get('user_full_name'),
            redirectPage: formData.get('redirect-page'),
            userRegistration: formData.get('registration'),
            continuePeriod: formData.get('continue-period'),
        };

        const periodAmount = formData.get('period-amount');
        if (periodAmount) {
            data = {
                ...data,
                continuePeriod: periodAmount.split('|')[0],
            };
        }
        (0,_helpers__WEBPACK_IMPORTED_MODULE_0__.setCookie)(`creating_order`, JSON.stringify(data), 3);
    }
}


/***/ }),

/***/ "./src/js/parts/helpers.js":
/*!*********************************!*\
  !*** ./src/js/parts/helpers.js ***!
  \*********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   anchorLinkScroll: function() { return /* binding */ anchorLinkScroll; },
/* harmony export */   closestPolyfill: function() { return /* binding */ closestPolyfill; },
/* harmony export */   copyToClipboard: function() { return /* binding */ copyToClipboard; },
/* harmony export */   debounce: function() { return /* binding */ debounce; },
/* harmony export */   equalHeights: function() { return /* binding */ equalHeights; },
/* harmony export */   fadeIn: function() { return /* binding */ fadeIn; },
/* harmony export */   fadeOut: function() { return /* binding */ fadeOut; },
/* harmony export */   getCookie: function() { return /* binding */ getCookie; },
/* harmony export */   isInViewport: function() { return /* binding */ isInViewport; },
/* harmony export */   loadFileName: function() { return /* binding */ loadFileName; },
/* harmony export */   setCookie: function() { return /* binding */ setCookie; },
/* harmony export */   setHeightEqualToWidth: function() { return /* binding */ setHeightEqualToWidth; },
/* harmony export */   throttle: function() { return /* binding */ throttle; },
/* harmony export */   trimParagraph: function() { return /* binding */ trimParagraph; },
/* harmony export */   validateField: function() { return /* binding */ validateField; }
/* harmony export */ });
/* harmony import */ var smoothscroll_polyfill__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! smoothscroll-polyfill */ "./node_modules/smoothscroll-polyfill/dist/smoothscroll.js");
/* harmony import */ var smoothscroll_polyfill__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(smoothscroll_polyfill__WEBPACK_IMPORTED_MODULE_0__);


// kick off the polyfill!
smoothscroll_polyfill__WEBPACK_IMPORTED_MODULE_0___default().polyfill();

/**
 * Fade Out method
 *
 * @param {string} el
 */
function fadeOut(el) {
    if (!el) {
        throw Error('"fadeOut function - "You didn\'t add required parameters');
    }

    const domElement =
        el instanceof HTMLElement ? el : document.querySelector(el);

    if (!domElement) {
        throw Error("domElement doesn't exist");
    }

    domElement.style.opacity = 1;

    (function fade() {
        if ((domElement.style.opacity -= 0.1) < 0) {
            domElement.style.display = 'none';
        } else {
            requestAnimationFrame(fade);
        }
    })();
}

/**
 * Fade In method
 *
 * @param {string} el      - element that need to fade in
 *
 * @param {string} display - display variant
 */
function fadeIn(el, display = 'block') {
    if (el === undefined) return;
    if (!el) {
        throw Error('"fadeIn function - "You didn\'t add required parameters');
    }

    const domElement = document.querySelector(el);

    if (!domElement) {
        throw Error("domElement doesn't exist");
    }

    domElement.style.opacity = 0;
    domElement.style.display = display || 'block';

    (function fade() {
        let val = parseFloat(domElement.style.opacity);
        if (!((val += 0.1) > 1)) {
            domElement.style.opacity = val;
            requestAnimationFrame(fade);
        }
    })();
}

/**
 *  Set equal height to selected elements calculated as bigger height
 *
 * @param {Array | string} elementsSelector  - selector for searching elements
 * @param {string} minOrMax          - Define what dimension should be calculated (minHeight or maxHeight)
 * @return {Array | string} elementsSelector
 */
function equalHeights(elementsSelector, minOrMax = 'max') {
    if (!elementsSelector) {
        throw Error(
            '"equalHeights function - "You didn\'t add required parameters'
        );
    }

    const heights = [];
    const elementsSelectorArr = Array.isArray(elementsSelector)
        ? elementsSelector
        : [...document.querySelectorAll(elementsSelector)];

    elementsSelectorArr.forEach((item) => {
        // eslint-disable-next-line no-param-reassign
        item.style.height = 'auto';
    });

    elementsSelectorArr.forEach((item) => {
        heights.push(item.offsetHeight);
    });

    const commonHeight =
        minOrMax === 'max'
            ? Math.max.apply(0, heights)
            : Math.min.apply(0, heights);

    elementsSelectorArr.forEach((item) => {
        // eslint-disable-next-line no-param-reassign
        item.style.height = `${commonHeight}px`;
    });

    return elementsSelector;
}

/**
 * Trim all paragraph from unneeded space symbol
 */
function trimParagraph() {
    [...document.querySelectorAll('p')].forEach((item) => {
        // eslint-disable-next-line no-param-reassign
        item.innerHTML = item.innerHTML.trim();
    });
}

/**
 * Check if element in viewport
 *
 * @param {HTMLElement | null} el
 * @param {number} offset - Adjustable offset value when element becomes visible
 * @return {boolean} Result of checking
 */
function isInViewport(el, offset = 100) {
    if (!el) {
        throw Error(
            '"isInViewport function - "You didn\'t add required parameters'
        );
    }

    const scroll = window.scrollY || window.pageYOffset;
    const boundsTop = el.getBoundingClientRect().top + offset + scroll;

    const viewport = {
        top: scroll,
        bottom: scroll + window.innerHeight,
    };

    const bounds = {
        top: boundsTop,
        bottom: boundsTop + el.clientHeight,
    };

    return (
        (bounds.bottom >= viewport.top && bounds.bottom <= viewport.bottom) ||
        (bounds.top <= viewport.bottom && bounds.top >= viewport.top)
    );
}

/**
 * Debounce function
 *
 * @param {Function | null} fn  - function that should be executed
 * @param {number} delay        - time delay
 * @return {Function}           - function to execute
 */
const debounce = (fn, delay = 1000) => {
    if (!fn) {
        throw Error(
            '"debounce function - "You didn\'t add required parameters'
        );
    }

    let timeout;

    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(undefined, args), delay);
    };
};

/**
 *
 * @param {Function | null} func  - function that should be executed
 * @param {number} ms             - time delay
 * @return {Function} wrapper     - function to execute
 */
const throttle = (func, ms) => {
    let isThrottled = false;
    let savedArgs;
    let savedThis;

    function wrapper() {
        if (isThrottled) {
            // (2)
            // eslint-disable-next-line prefer-rest-params
            savedArgs = arguments;
            // eslint-disable-next-line @typescript-eslint/no-this-alias
            savedThis = this;
            return;
        }

        // eslint-disable-next-line prefer-rest-params
        func && func.apply(this, arguments); // (1)

        isThrottled = true;

        setTimeout(() => {
            isThrottled = false; // (3)
            if (savedArgs) {
                wrapper.apply(savedThis, savedArgs);
                // eslint-disable-next-line no-multi-assign
                savedArgs = savedThis = null;
            }
        }, ms);
    }

    return wrapper;
};

/**
 * Copy to clipboard
 *
 * @param {HTMLElement | null} parent
 * @param {HTMLElement | null} element -  element that  contain value to copy
 */
const copyToClipboard = (parent, element) => {
    if (!parent || !element) {
        throw Error(
            '"copyToClipboard function - "You didn\'t add required parameters'
        );
    }

    const el = document.createElement('textarea');
    el.value = element.value;
    document.body.appendChild(el);
    el.select();

    try {
        const successful = document.execCommand('copy');

        if (successful) {
            parent.classList.add('copied');

            setTimeout(() => {
                parent.classList.remove('copied');
            }, 3000);
        }
    } catch (err) {
        // eslint-disable-next-line no-console
        console.log('Oops, unable to copy');
    }

    document.body.removeChild(el);
};

/**
 * Test value with regex
 *
 * @param {string} fieldType  - The allowed type of the fields
 * @param {string} value
 * @return {boolean} Result of checking
 */
const validateField = (fieldType = null, value = null) => {
    if (!fieldType || !value) {
        throw Error(
            '"validateField function - "You didn\'t add required parameters'
        );
    }

    const phoneREGEX = /^[0-9+]{6,13}$/;
    const nameREGEX = /^[a-zA-Zа-яА-ЯёЁіІїЇєЄґҐ'’ʼ\s-]+$/;
    const postalREGEX = /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i;
    const emailREGEX = /^[\w+.-]+@\w+([.-]?\w+)*(\.\w{2,3})+$/;
    const dummyREGEX = /^[a-zA-Z0-9]{2,30}$/;

    let checkResult = false;

    switch (fieldType) {
        case 'name':
        case 'first_name':
        case 'last_name':
            checkResult = nameREGEX.test(value);
            break;
        case 'phone':
            checkResult = phoneREGEX.test(value);
            break;
        case 'postal':
            checkResult = postalREGEX.test(value);
            break;
        case 'email':
            checkResult = emailREGEX.test(value);
            break;
        case 'price':
            checkResult = dummyREGEX.test(value);
            break;
        case 'aim':
            checkResult = dummyREGEX.test(value);
            break;
        case 'date':
            checkResult = dummyREGEX.test(value);
            break;
        case 'subject':
        case 'company':
            checkResult = dummyREGEX.test(value);
            break;
        default:
            checkResult = true;
            break;
    }

    return checkResult;
};

/**
 * Polyfill for closest method
 */
function closestPolyfill() {
    if (window.Element && !Element.prototype.closest) {
        Element.prototype.closest = (s) => {
            const matches = (
                this.document || this.ownerDocument
            ).querySelectorAll(s);
            let i;
            // eslint-disable-next-line @typescript-eslint/no-this-alias
            let el = this;
            do {
                i = matches.length;
                // eslint-disable-next-line no-empty
                while (--i >= 0 && matches.item(i) !== el) {}
            } while (i < 0 && (el = el.parentElement));
            return el;
        };
    }
}

/**
 * Smooth scroll to element on page
 *
 * @param {string|null} elementsSelector string -- css selector (anchor links)
 * @param {Function|null} callback function     -- callback for some additional actions
 * @param {number} offset function              -- offset in px
 */

function anchorLinkScroll(
    elementsSelector = null,
    callback = null,
    offset = 0
) {
    if (!elementsSelector) {
        throw Error(
            '"anchorLinkScroll function - "You didn\'t add correct selector for anchor links'
        );
    }

    const elements = document.querySelectorAll(elementsSelector);

    elements &&
        [...elements].forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();

                const elHref =
                    event.target.nodeName === 'A'
                        ? event.target.getAttribute('href')
                        : event.target.dataset.href;

                if (!elHref.startsWith('#')) {
                    window.location = elHref;
                }

                const ANCHOR_ELEMENT = document.querySelector(elHref);

                ANCHOR_ELEMENT &&
                    window.scroll({
                        behavior: 'smooth',
                        left: 0,
                        top: ANCHOR_ELEMENT.offsetTop + offset,
                    });

                if (callback) callback(event);
            });
        });
}

const setHeightEqualToWidth = (elementSelector) => {
    const elements = document.querySelectorAll(elementSelector);
    // @ts-ignore
    elements &&
        elements.forEach((element) => {
            const width = element.offsetWidth;
            // eslint-disable-next-line no-param-reassign
            element.style.height = `${width}px`;
        });
};

const loadFileName = () => {
    const inputFileBtn = document.querySelector('.js-file-button');
    if (!inputFileBtn) return;
    const inputFile = inputFileBtn.querySelector('input');
    const spanText = inputFileBtn.querySelector('span');

    inputFile &&
        inputFile.addEventListener('change', () => {
            if (spanText) {
                spanText.innerText = inputFile.files[0].name;
            }
        });
};

const setCookie = (name, value, hours) => {
    let expires = '';
    if (hours) {
        const date = new Date();
        date.setTime(date.getTime() + hours * 60 * 60 * 1000);
        expires = `expires=${date.toUTCString()}`;
    }
    document.cookie = `${name}=${value || ''};${expires};path=/`;
};

const getCookie = (name) => {
    const nameEQ = `${name}=`;
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1, c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
            return c.substring(nameEQ.length, c.length);
        }
    }
    return null;
};


/***/ }),

/***/ "./src/js/parts/navi-tabs.js":
/*!***********************************!*\
  !*** ./src/js/parts/navi-tabs.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * Tabs Navigation functionality
 *
 * @param {string} tabSwitchSelectors  -  css selectors
 * @param {string} tabPanelSelectors   -  css selectors
 * @param {boolean} closeToClick       -  close child tab by click (default false)
 */
const tabsNavigation = (
    tabSwitchSelectors,
    tabPanelSelectors,
    closeToClick = false
) => {
    tabSwitchSelectors &&
        [...document.querySelectorAll(tabSwitchSelectors)].forEach((item) => {
            item.addEventListener('click', (event) => {
                event.preventDefault();

                const TAB_ID =
                    event.target.nodeName === 'A'
                        ? event.target.getAttribute('href')
                        : event.target.dataset.href;

                if (closeToClick && event.target.classList.contains('active')) {
                    // Remove active state from all switch elements
                    [...document.querySelectorAll(tabSwitchSelectors)].forEach(
                        (el) => el.classList.remove('active')
                    );

                    // Remove active state from all tabs panels
                    [...document.querySelectorAll(tabPanelSelectors)].forEach(
                        (el) => el.classList.remove('active')
                    );
                    return;
                }
                // Remove active state from all switch elements
                [...document.querySelectorAll(tabSwitchSelectors)].forEach(
                    (el) => el.classList.remove('active')
                );

                // Remove active state from all tabs panels
                [...document.querySelectorAll(tabPanelSelectors)].forEach(
                    (el) => el.classList.remove('active')
                );

                // Set active state to current
                event.target.classList.add('active');
                document.querySelector(`#${TAB_ID}`).classList.add('active');

                // force trigger resize event for the document
                if (document.createEvent) {
                    window.dispatchEvent(new Event('resize'));
                } else {
                    document.body.fireEvent('onresize');
                }
            });
        });
};

/* harmony default export */ __webpack_exports__["default"] = (tabsNavigation);


/***/ }),

/***/ "./src/js/parts/popup-window.js":
/*!**************************************!*\
  !*** ./src/js/parts/popup-window.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ Popup; }
/* harmony export */ });
/* harmony import */ var _helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers */ "./src/js/parts/helpers.js");


class Popup {
    constructor() {
        this.body = window.document.querySelector('body');
        this.html = window.document.querySelector('html');
    }

    /**
     * Force Close All opened popup window
     * and clear the traces of an opened popup window
     */
    forceCloseAllPopup() {
        [...window.document.querySelectorAll('.popup')].forEach((item) => {
            const allErrorsInPopup = item.querySelectorAll(
                '.wpcf7-not-valid-tip'
            );
            const bottomMessage = item.querySelectorAll(
                '.wpcf7-response-output'
            );
            const form = item.querySelectorAll('form'); // reset()

            if (allErrorsInPopup) {
                // eslint-disable-next-line no-shadow
                allErrorsInPopup.forEach((item) => {
                    item.remove();
                });
            }

            if (bottomMessage) {
                // eslint-disable-next-line no-shadow
                bottomMessage.forEach((item) => {
                    item.remove();
                });
            }

            if (form) {
                // eslint-disable-next-line no-shadow
                form.forEach((item) => {
                    item.reset();
                });
            }

            (0,_helpers__WEBPACK_IMPORTED_MODULE_0__.fadeOut)(item);
            const MAIL_SENT_OK_BOX = item.querySelector('.wpcf7-mail-sent-ok');
            if (MAIL_SENT_OK_BOX) {
                MAIL_SENT_OK_BOX.style.display = 'none';
            }
        });

        this.body.classList.remove('popup-opened');
        this.html.classList.remove('popup-opened');
    }

    /**
     * Open selected popup window
     *
     * @param {string} popupSelector - css selector of popup that should be opened
     * @param {number} timeOut - ms
     */
    openOnePopup(popupSelector = null, timeOut = 1000) {
        this.forceCloseAllPopup();

        setTimeout(() => {
            this.body.classList.add('popup-opened');
            this.html.classList.add('popup-opened');
            (0,_helpers__WEBPACK_IMPORTED_MODULE_0__.fadeIn)(popupSelector);
        }, timeOut);
    }

    /**
     * Opening popup window
     */
    openPopup() {
        this.body.addEventListener('click', (event) => {
            if (
                ![...event.target.classList].includes('js-open-popup-activator')
            ) {
                return false;
            }

            event.preventDefault();
            const elHref =
                event.target.nodeName === 'A'
                    ? event.target.getAttribute('href')
                    : event.target.dataset.href;

            this.body.classList.add('popup-opened');
            // this.html.classList.add('popup-opened');
            (0,_helpers__WEBPACK_IMPORTED_MODULE_0__.fadeIn)(elHref);
            return true;
        });
    }

    /**
     * Closing popup window
     */
    closePopup() {
        this.body.addEventListener('click', (event) => {
            // Check if user click on close element
            if (![...event.target.classList].includes('js-popup-close')) {
                return false;
            }
            const popupLockPost = document.querySelectorAll('.js-popup-form');

            popupLockPost &&
                popupLockPost.forEach((popup) => {
                    popup.classList.remove('sent');
                });

            event.preventDefault();
            this.forceCloseAllPopup();
            return true;
        });

        // Checking ESC button for closing opened popup window
        window.document.addEventListener('keydown', (event) => {
            if (event.keyCode === 27) {
                this.forceCloseAllPopup();
            }
        });
    }

    init() {
        this.openPopup();
        this.closePopup();
    }
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!****************************!*\
  !*** ./src/js/frontend.ts ***!
  \****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_frontend_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/frontend.scss */ "./src/scss/frontend.scss");
/* harmony import */ var _components_accordion__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/accordion */ "./src/js/components/accordion.ts");
/* harmony import */ var _components_menuActions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/menuActions */ "./src/js/components/menuActions.ts");
/* harmony import */ var _components_profileFunctionality__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/profileFunctionality */ "./src/js/components/profileFunctionality.ts");
/* harmony import */ var _parts_formsActionsClass__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./parts/formsActionsClass */ "./src/js/parts/formsActionsClass.js");
/* harmony import */ var _parts_helpers__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./parts/helpers */ "./src/js/parts/helpers.js");
/* harmony import */ var _parts_navi_tabs__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./parts/navi-tabs */ "./src/js/parts/navi-tabs.js");
/* harmony import */ var _parts_popup_window__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./parts/popup-window */ "./src/js/parts/popup-window.js");








function ready() {
  var blocks = document.querySelectorAll('.js-anim-activate');
  var siteHeader = document.querySelector('.js-site-header');
  var popupInstance = new _parts_popup_window__WEBPACK_IMPORTED_MODULE_7__["default"]();
  var profileFunctionality = new _components_profileFunctionality__WEBPACK_IMPORTED_MODULE_3__["default"]();
  var formsActionClass = new _parts_formsActionsClass__WEBPACK_IMPORTED_MODULE_4__["default"](popupInstance, _parts_helpers__WEBPACK_IMPORTED_MODULE_5__.validateField);
  popupInstance.init();
  profileFunctionality.init();
  formsActionClass.init();
  var paySuccessResponse = document.querySelector('#pay-success-response');
  if (paySuccessResponse) {
    popupInstance.openOnePopup('#pay-success-response');
    if (paySuccessResponse.classList.contains('profile-page')) {
      setTimeout(function () {
        window.location.reload();
      }, 3000);
    }
  }
  (0,_parts_helpers__WEBPACK_IMPORTED_MODULE_5__.anchorLinkScroll)('a[href^="#"]:not(.js-open-popup-activator):not(.js-tab-link)', function (event) {
    var _a;
    var currentUrl = window.location.href;
    var cleanUrl = currentUrl.split('#')[0];
    var siteUrl = "".concat(var_from_php.site_url, "/");
    var linkUrl = (_a = event === null || event === void 0 ? void 0 : event.target) === null || _a === void 0 ? void 0 : _a.getAttribute('href');
    var anchorBlock = document.querySelector(linkUrl);
    siteHeader && siteHeader.classList.remove('menu-opened');
    document.body.classList.remove('popup-opened');
    popupInstance.closePopup();
    if (siteUrl !== cleanUrl && !anchorBlock) {
      window.location.href = "".concat(siteUrl).concat(linkUrl);
    }
  }, -70);
  (0,_components_menuActions__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_components_accordion__WEBPACK_IMPORTED_MODULE_1__["default"])();
  (0,_components_accordion__WEBPACK_IMPORTED_MODULE_1__.initInnerAccordion)();
  (0,_parts_navi_tabs__WEBPACK_IMPORTED_MODULE_6__["default"])('.js-tab-link', '.js-tab-panel');
  (0,_parts_helpers__WEBPACK_IMPORTED_MODULE_5__.loadFileName)();
  if (window.scrollY > 100) {
    siteHeader && siteHeader.classList.add('scrolled');
  } else {
    siteHeader && siteHeader.classList.remove('scrolled');
  }
  blocks && blocks.forEach(function (el) {
    var block = el;
    (0,_parts_helpers__WEBPACK_IMPORTED_MODULE_5__.isInViewport)(block) && block.classList.add('viewed');
  });
  window.document.addEventListener('scroll', function () {
    if (window.scrollY > 100) {
      siteHeader && siteHeader.classList.add('scrolled');
    } else {
      siteHeader && siteHeader.classList.remove('scrolled');
    }
    blocks && blocks.forEach(function (el) {
      var block = el;
      (0,_parts_helpers__WEBPACK_IMPORTED_MODULE_5__.isInViewport)(block) && block.classList.add('viewed');
    });
  });
  document.body.addEventListener('click', function (e) {
    var target = e.target;
    var role = target.dataset.role;
    if (target.classList.contains('js-popup-in-popup')) {
      popupInstance.forceCloseAllPopup();
      setTimeout(function () {
        popupInstance.openOnePopup(target.dataset.href);
      }, 1000);
    }
    if (!role) return;
    switch (role) {
      case 'mobile-menu':
        {
          e.preventDefault();
          siteHeader && siteHeader.classList.toggle('menu-opened');
          document.body.classList.toggle('popup-opened');
          break;
        }
      default:
        break;
    }
  });
  window.document.addEventListener('wpcf7mailsent', function () {
    var header = document.querySelector('.js-site-header');
    setTimeout(function () {
      if (header && header.dataset.thank_you_page) {
        window.location.replace(header.dataset.thank_you_page);
      }
    }, 2000);
  });
}
window.document.addEventListener('DOMContentLoaded', ready);
}();
/******/ })()
;
//# sourceMappingURL=frontend.js.map