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
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   hoverClickEvent: function() { return /* binding */ hoverClickEvent; }
/* harmony export */ });
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
          subMenu.addEventListener('click', function (e) {
            e.stopPropagation();
          });
        }
      });
    });
  }
};

/***/ }),

/***/ "./src/js/components/profileFunctionality.ts":
/*!***************************************************!*\
  !*** ./src/js/components/profileFunctionality.ts ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ProfileFunctionality: function() { return /* binding */ ProfileFunctionality; }
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : String(i); }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var ProfileFunctionality = /*#__PURE__*/function () {
  function ProfileFunctionality() {
    var _this = this;
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
    this.addPauseListenerToAllVideos = function () {
      var videos = document.querySelectorAll('video');
      videos && videos.forEach(function (el) {
        var video = el;
        video.addEventListener('pause', function () {
          var parentPanel = video.closest(".js-tab-panel");
          _this.saveVideoTimeData(video, parentPanel.id);
        });
      });
    };
  }
  _createClass(ProfileFunctionality, [{
    key: "init",
    value: function init() {
      this.playVideoByClickInit();
      this.addPauseListenerToAllVideos();
      this.createProfileVideoData('courses');
      this.createProfileVideoData('lectures');
      this.playNextVideo();
      this.editFormFieldAddEvent();
      this.addEventfetchUserDataForm();
    }
  }, {
    key: "loadDataAndPlayVideo",
    value: function loadDataAndPlayVideo(playBtnData) {
      var _a, _b, _c, _d, _e, _f;
      if (!playBtnData) return;
      var containerId = (_a = playBtnData.dataset) === null || _a === void 0 ? void 0 : _a.video_container_id;
      var videoUrl = (_b = playBtnData.dataset) === null || _b === void 0 ? void 0 : _b.video_url;
      var videoTitle = (_c = playBtnData.dataset) === null || _c === void 0 ? void 0 : _c.video_title;
      var videoId = (_d = playBtnData.dataset) === null || _d === void 0 ? void 0 : _d.video_id;
      var videoPlayingByBtn = (_e = playBtnData.dataset) === null || _e === void 0 ? void 0 : _e.play_btn_id;
      var videoPauseTime = parseFloat((_f = playBtnData.dataset) === null || _f === void 0 ? void 0 : _f.video_pause_time);
      var videoContainer = document.querySelector("#".concat(containerId));
      var videoTitleContainer = videoContainer === null || videoContainer === void 0 ? void 0 : videoContainer.querySelector('.js-video-title');
      if (videoTitleContainer) {
        videoTitleContainer.innerHTML = "".concat(videoTitle);
      }
      if (!videoContainer) return;
      var video = videoContainer.querySelector('video');
      this.pauseAllVideos();
      if (video) {
        video.src = videoUrl;
        video.dataset.video_id = videoId;
        video.dataset.video_playing_by_btn = videoPlayingByBtn;
        video.currentTime = videoPauseTime;
        video.play();
      }
    }
  }, {
    key: "playVideoByClickInit",
    value: function playVideoByClickInit() {
      var _this2 = this;
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
          removeAllActiveBtns();
          _this2.pauseAllVideos();
          button.classList.add('playing-video');
          _this2.loadDataAndPlayVideo(button);
        });
      });
    }
  }, {
    key: "saveVideoTimeData",
    value: function saveVideoTimeData(video, learninMaterialType) {
      var _a, _b, _c, _d;
      if (!((_a = video.dataset) === null || _a === void 0 ? void 0 : _a.video_id)) return;
      var videoDuration = video.duration;
      var videoPauseTime = video.currentTime;
      var programmId = (_b = video.dataset.video_id) === null || _b === void 0 ? void 0 : _b.split('_')[0];
      var blockId = (_c = video.dataset.video_id) === null || _c === void 0 ? void 0 : _c.split('_')[1];
      var videoId = (_d = video.dataset.video_id) === null || _d === void 0 ? void 0 : _d.split('_')[2];
      var viewed = false;
      if (videoPauseTime && videoDuration) {
        viewed = +videoPauseTime / +videoDuration >= 0.9;
      }
      var currentProgrammPath = this.profileData[learninMaterialType].programms[programmId];
      var currentBlockPath = currentProgrammPath === null || currentProgrammPath === void 0 ? void 0 : currentProgrammPath.blocks[blockId];
      currentBlockPath.videos[videoId] = Object.assign(Object.assign({}, currentBlockPath.videos[videoId]), {
        videoDuration: videoDuration,
        videoPauseTime: videoPauseTime,
        isVideoViewed: viewed
      });
      this.changeBlockStatus(currentBlockPath);
      this.changePassedBlocksCount(currentProgrammPath);
      console.log('reated profileData', this.profileData);
      this.fetchDataToBackend(this.profileData);
    }
  }, {
    key: "createProfileVideoData",
    value: function createProfileVideoData(learninMaterialType) {
      var _this3 = this;
      var mainContainer = document.querySelector("#".concat(learninMaterialType));
      if (!mainContainer) return;
      var playVideoBtns = mainContainer.querySelectorAll('.js-play-video-btn');
      if (mainContainer && mainContainer.dataset.user_id) {
        this.profileData.userId = +mainContainer.dataset.user_id;
      }
      playVideoBtns && playVideoBtns.forEach(function (el) {
        var _a, _b, _c, _d, _e, _f, _g, _h, _j, _k;
        var button = el;
        var programmId = (_b = (_a = button.dataset) === null || _a === void 0 ? void 0 : _a.video_id) === null || _b === void 0 ? void 0 : _b.split('_')[0];
        var blockId = (_d = (_c = button.dataset) === null || _c === void 0 ? void 0 : _c.video_id) === null || _d === void 0 ? void 0 : _d.split('_')[1];
        var videoId = (_e = button.dataset.video_id) === null || _e === void 0 ? void 0 : _e.split('_')[2];
        var videoTitle = button.dataset.video_title;
        var videoDuration = (_f = button.dataset) === null || _f === void 0 ? void 0 : _f.video_duration;
        var videoPauseTime = (_g = button.dataset) === null || _g === void 0 ? void 0 : _g.video_pause_time;
        var videoIsViewed = (_h = button.dataset) === null || _h === void 0 ? void 0 : _h.video_viewed;
        var blocksPassedCount = (_j = button.dataset) === null || _j === void 0 ? void 0 : _j.passed_blocks_count;
        if (!programmId || !blockId || !videoId) return;
        if (!_this3.profileData[learninMaterialType].programms[programmId]) {
          _this3.profileData[learninMaterialType].programms[programmId] = {
            programmId: +programmId.split('-')[1] || null,
            blocksPassed: blocksPassedCount,
            blocks: {}
          };
        }
        if (!_this3.profileData[learninMaterialType].programms[programmId].blocks[blockId]) {
          var currentBlock = _this3.getCurrentBlock(programmId, blockId);
          _this3.profileData[learninMaterialType].programms[programmId].blocks[blockId] = {
            blockStatus: ((_k = currentBlock === null || currentBlock === void 0 ? void 0 : currentBlock.dataset) === null || _k === void 0 ? void 0 : _k.block_status) || null,
            videos: {}
          };
        }
        var currentProgrammPath = _this3.profileData[learninMaterialType].programms[programmId];
        var currentBlockPath = currentProgrammPath === null || currentProgrammPath === void 0 ? void 0 : currentProgrammPath.blocks[blockId];
        currentBlockPath.videos[videoId] = {
          videoTitle: videoTitle || null,
          videoId: videoId || null,
          videoDuration: videoDuration || null,
          videoPauseTime: videoPauseTime || null,
          isVideoViewed: videoIsViewed || ''
        };
      });
      console.log('created profileData', this.profileData);
    }
  }, {
    key: "pauseAllVideos",
    value: function pauseAllVideos() {
      var videos = document.querySelectorAll('video');
      videos && videos.forEach(function (el) {
        var video = el;
        video.pause();
      });
    }
  }, {
    key: "changeBlockStatus",
    value: function changeBlockStatus(currentBlockObject) {
      if (!currentBlockObject) return;
      var videosArray = Object.values(currentBlockObject.videos);
      var isBlockPassed = videosArray.every(function (video) {
        return video.isVideoViewed;
      });
      var isBlockNotPassed = videosArray.every(function (video) {
        return !video.isVideoViewed;
      });
      if (isBlockPassed) {
        currentBlockObject.blockStatus = 'passed';
      } else if (isBlockNotPassed) {
        currentBlockObject.blockStatus = 'not-passed';
      } else {
        currentBlockObject.blockStatus = 'in-progress';
      }
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
      var _this4 = this;
      var playNextBtns = document.querySelectorAll('.js-next-video-btn');
      playNextBtns && playNextBtns.forEach(function (el) {
        var btn = el;
        btn.addEventListener('click', function () {
          var videoBlock = btn.closest('.js-programm-block');
          var playVideoBtns = videoBlock.querySelectorAll('.js-play-video-btn');
          var playVideoBtn = videoBlock.querySelector('.js-play-video-btn.playing-video');
          var nextPLayBtn = playVideoBtn === null || playVideoBtn === void 0 ? void 0 : playVideoBtn.nextSibling;
          if (nextPLayBtn) {
            playVideoBtns && playVideoBtns.forEach(function (el) {
              var btn = el;
              btn.classList.remove('playing-video');
            });
            _this4.loadDataAndPlayVideo(nextPLayBtn);
            nextPLayBtn.classList.add('playing-video');
          }
        });
      });
    }
  }, {
    key: "fetchDataToBackend",
    value: function fetchDataToBackend(profileData) {
      if (!profileData) return;
      fetch("".concat(var_from_php.ajax_url, "?action=save_video_data"), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(profileData)
      });
    }
  }, {
    key: "getCurrentBlock",
    value: function getCurrentBlock(programmId, blockId) {
      var blockIdstring = "".concat(programmId, "_").concat(blockId, "-container");
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
          console.log(res.data);
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
          inpus && inpus.forEach(function (el) {
            return setInputDefaultState(el);
          });
          var parentWrapper = btn.closest('.js-inner-input-wrapper');
          var input = parentWrapper === null || parentWrapper === void 0 ? void 0 : parentWrapper.querySelector('input[type="text"]');
          input.classList.add('focus');
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
/* harmony export */   FormsActionsClass: function() { return /* binding */ FormsActionsClass; }
/* harmony export */ });
class FormsActionsClass {
    constructor(popupInstance, validateField) {
        this.popupInstance = popupInstance;
        this.validateField = validateField; // function
    }

    init() {
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
                const res = await this.fetchToAction(loginForm, 'user_login_ajax');

                this.setDataToRespContainer(res, loginForm);
                if (res.success) {
                    window.location.reload();
                }
            });
    };

    checkFormFields() {
        const formInputs = document.querySelectorAll('input');
        if (!formInputs) return;
        let repeatPassword = '';

        const checkAllInputs = (target) => {
            let allInputsValid = true;

            const parentForm = target.closest('form');

            if (!parentForm) return;

            const allInnerInputs = parentForm.querySelectorAll('input');
            const formSubmit = parentForm.querySelector('input[type="submit"]');

            allInnerInputs.forEach((input) => {
                const inputContainer = input.closest('.js-inner-input-wrapper');

                if (
                    !input.name ||
                    !input.value ||
                    input.type === 'hidden' ||
                    input.name === 'password' ||
                    input.name === 'password-repeat'
                )
                    return;

                const isValid = this.validateField(input.name, input.value);

                if (isValid) {
                    input && input.classList.add('valid');
                    input && input.classList.remove('not-valid');
                    inputContainer && inputContainer.classList.add('valid');
                    inputContainer && inputContainer.classList.remove('not-valid');
                } else {
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

                const formSubmit = parentForm.querySelector('input[type="submit"]');
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
    };

    restorePasswordFormFetch() {
        const forgotPasswordForm = document.querySelector('.js-forgot-password-form');

        if (!forgotPasswordForm) return;

        forgotPasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await this.fetchToAction(forgotPasswordForm, 'forgot_password');

            if (!res.success) {
                this.setDataToRespContainer(res, forgotPasswordForm);
            } else {
                this.popupInstance.openOnePopup('#forgot-password-popup');
            }
        });
    };

    getAccessFormFetch() {
        const getAccessForm = document.querySelector('.js-get-access-form');

        if (!getAccessForm) return;

        getAccessForm &&
            getAccessForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                //FETCH TO FAKE PAY SYSTEM
                const payRes = await this.paySystemFetch();

                console.log('payRes', payRes);
                if (payRes.success) {
                    const res = await this.fetchToAction(getAccessForm, 'register_login_user');
                    if (res.success) {
                        this.popupInstance.forceCloseAllPopup();
                        this.popupInstance.openOnePopup('#pay-success-response');

                    } else {
                        this.setDataToRespContainer(res, getAccessForm);
                    }
                }

            });
    };

    async fetchToAction(form, actionName) {
        const formData = new FormData(form);
        const resJSON = await fetch(`${var_from_php.ajax_url}?action=${actionName}`, {
            method: 'POST',
            body: formData,
        }
        );

        const res = await resJSON.json();
        return res;
    }

    setNewPasswordFormFetch() {
        const newPasswordForm = document.querySelector('.js-set-new-password-form');

        if (!newPasswordForm) return;

        newPasswordForm &&
            newPasswordForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(newPasswordForm);

                const resJSON = await fetch(
                    `${var_from_php.ajax_url}?action=set_new_password`,
                    {
                        method: 'POST',
                        body: formData,
                    }
                );

                const res = await resJSON.json();

                this.setDataToRespContainer(res, newPasswordForm);

            });
    };

    buyProgrammFormFetch() {
        const buyProgrammForm = document.querySelector('.js-buy-programm-form');

        if (!buyProgrammForm) return;

        buyProgrammForm &&
            buyProgrammForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                //FETCH TO FAKE PAY SYSTEM
                const res = await this.paySystemFetch();
                console.log('res.success', res.success);
                if (res.success) {
                    this.setDataToRespContainer(res, buyProgrammForm);
                    const res2 = await this.fetchToAction(buyProgrammForm, 'add_programm_to_user');

                    if (res2.success) {
                        this.setDataToRespContainer(res2, buyProgrammForm);
                    }
                }
            });
    }

    async paySystemFetch() {
        const resJSON = await fetch(`${var_from_php.ajax_url}?action=FAKE_PAY_SYSTEM`);
        const res = await resJSON.json();

        return res;
    }

    setDataToRespContainer(res, parentForm) {
        if (!parentForm || !res) return;

        const respContainer = parentForm.querySelector('.js-response-container');
        if (!respContainer) return;

        const additionalClass = res.success ? 'success' : 'error';
        const paragrahp = document.createElement('p');
        paragrahp.classList.add(additionalClass);
        paragrahp.innerText = res.data;
        respContainer.innerHTML = '';
        respContainer.appendChild(paragrahp);
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
/* harmony export */   isInViewport: function() { return /* binding */ isInViewport; },
/* harmony export */   loadFileName: function() { return /* binding */ loadFileName; },
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
    const nameREGEX = /^[a-zA-Z]{2,30}$/;
    const postalREGEX = /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i;
    const emailREGEX = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
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
                while (--i >= 0 && matches.item(i) !== el) { }
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

                if (callback) callback();
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
  var profileFunctionality = new _components_profileFunctionality__WEBPACK_IMPORTED_MODULE_3__.ProfileFunctionality();
  var formsActionClass = new _parts_formsActionsClass__WEBPACK_IMPORTED_MODULE_4__.FormsActionsClass(popupInstance, _parts_helpers__WEBPACK_IMPORTED_MODULE_5__.validateField);
  popupInstance.init();
  profileFunctionality.init();
  formsActionClass.init();
  (0,_parts_helpers__WEBPACK_IMPORTED_MODULE_5__.anchorLinkScroll)('a[href^="#"]:not(.js-open-popup-activator):not(.js-tab-link)', function () {
    siteHeader && siteHeader.classList.remove('menu-opened');
    document.body.classList.remove('popup-opened');
    popupInstance.closePopup();
  }, -70);
  (0,_components_menuActions__WEBPACK_IMPORTED_MODULE_2__.hoverClickEvent)();
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
  window.document.addEventListener('wpcf7mailsent', function (event) {
    var siteHeader = document.querySelector('.js-site-header');
    setTimeout(function () {
      if (siteHeader && siteHeader.dataset.thank_you_page) {
        window.location.replace(siteHeader.dataset.thank_you_page);
      }
    }, 2000);
  });
}
window.document.addEventListener('DOMContentLoaded', ready);
}();
/******/ })()
;
//# sourceMappingURL=frontend.js.map