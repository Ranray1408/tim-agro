interface videoData {
    videoTitle: string | null;
    videoId: string | null;
    videoDuration: number | null;
    videoPauseTime: number | null;
    isVideoViewed: boolean;
}

interface blockData {
    blockStatus: string | null;
    blockId: string;
    // Programm Vlock Video
    videos: {
        [key: string]: videoData;
    };
}

interface profileData {
    userId: number;
    courses: {
        programmType: string;
        programms: {
            // Programm
            [key: string]: {
                programmId: number | null;
                blocksPassed: number | null;
                blocks: {
                    // Programm Block
                    [key: string]: blockData;
                };
            };
        };
    };
    lectures: {
        programmType: string;
        programms: {
            // Programm
            [key: string]: {
                programmId: number | null;
                blocksPassed: number | null;
                blocks: {
                    // Programm Block
                    [key: string]: blockData;
                };
            };
        };
    };
}

export default class ProfileFunctionality {
    profileData: profileData = {
        userId: 0,
        courses: {
            programmType: 'courses',
            programms: {},
        },
        lectures: {
            programmType: 'lectures',
            programms: {},
        },
    };

    initedPlayer = null;

    // Main init method
    init() {
        this.playVideoByClickInit();
        this.createProfileVideoData('courses');
        this.createProfileVideoData('lectures');
        this.playNextVideo();
        // User info form
        this.editFormFieldAddEvent();
        this.addEventfetchUserDataForm();

        this.initPlayerOnOpenBlock();
    }

    // Loading video data by click
    loadDataAndPlayVideo(playBtnData) {
        if (!playBtnData) return;

        const containerId = playBtnData.dataset?.video_container_id;
        const videoTitle = playBtnData.dataset?.video_title;
        const videoId = playBtnData.dataset?.video_id;
        const videoPauseTime = parseFloat(playBtnData.dataset?.video_pause_time) ?? null;

        const videoContainer = document.querySelector(`[data-video_container_id="${containerId}"]`) as HTMLElement;
        const blockVideoWrapper = videoContainer.closest('.js-block-video') as HTMLElement;
        const videoTitleContainer = blockVideoWrapper?.querySelector('.js-video-title') as HTMLElement;

        if (videoTitleContainer) {
            videoTitleContainer.innerText = `${videoTitle}`;
        }

        if (!videoContainer) return;
        videoContainer.dataset.video_id = playBtnData.dataset.video_id;

        this.loadPDFButtons(playBtnData);
        const onPauseCallback = (pauseInfo) => {
            // eslint-disable-next-line no-param-reassign
            playBtnData.dataset.video_pause_time = pauseInfo.seconds;
        };

        // @ts-ignore
        this.initVimeoPlayer(this.initedPlayer, videoId, true, videoPauseTime, onPauseCallback);
    }

    loadPDFButtons(playBtnData) {
        if (!playBtnData || !playBtnData.dataset || !playBtnData.dataset.video_files) {
            console.error('Video files data is missing.');
            return;
        }

        const videoFiles = JSON.parse(playBtnData.dataset.video_files);
        const parentBlock = playBtnData.closest('.js-programm-block');

        let html = '';

        videoFiles &&
            videoFiles.forEach((item) => {
                if (!item.file && !item.file) return;

                html += `<a target="_blank" href="${item.file}" class="green-transparent">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.06973 15.441C7.06973 15.0424 6.79316 14.8047 6.30519 14.8047C6.10592 14.8047 5.97098 14.8244 5.90039 14.8432V16.122C5.98397 16.1408 6.08671 16.1471 6.22833 16.1471C6.74846 16.1471 7.06973 15.8843 7.06973 15.441Z" fill="white"/>
                            <path d="M10.0907 14.8179C9.87221 14.8179 9.73099 14.8367 9.64746 14.8563V17.6885C9.73104 17.7081 9.86599 17.7081 9.98799 17.7081C10.8745 17.7144 11.4527 17.2264 11.4527 16.1924C11.4589 15.2933 10.9321 14.8179 10.0907 14.8179Z" fill="white"/>
                            <path d="M15.703 0H6.06363C4.65541 0 3.50927 1.14694 3.50927 2.55436V12H3.25978C2.69141 12 2.23047 12.4604 2.23047 13.0293V19.2717C2.23047 19.8405 2.69136 20.3009 3.25978 20.3009H3.50927V21.4456C3.50927 22.8546 4.65541 24 6.06363 24H19.2161C20.6235 24 21.7698 22.8545 21.7698 21.4456V6.0455L15.703 0ZM4.93078 14.1558C5.23243 14.1049 5.65644 14.0664 6.25383 14.0664C6.85754 14.0664 7.28782 14.1817 7.57693 14.4131C7.8531 14.6313 8.03947 14.9913 8.03947 15.415C8.03947 15.8386 7.89825 16.1988 7.6413 16.4427C7.30709 16.7573 6.81284 16.8986 6.23467 16.8986C6.10599 16.8986 5.99065 16.8922 5.90046 16.8797V18.4276H4.93078V14.1558ZM19.2161 22.4356H6.06363C5.51836 22.4356 5.07434 21.9916 5.07434 21.4456V20.3009H17.3352C17.9036 20.3009 18.3645 19.8405 18.3645 19.2717V13.0293C18.3645 12.4604 17.9036 12 17.3352 12H5.07434V2.55436C5.07434 2.00989 5.51841 1.56587 6.06363 1.56587L15.1178 1.55641V4.90314C15.1178 5.88068 15.9109 6.67459 16.8892 6.67459L20.1677 6.66518L20.2046 21.4455C20.2046 21.9916 19.7614 22.4356 19.2161 22.4356ZM8.66473 18.408V14.1558C9.02438 14.0986 9.49314 14.0664 9.98783 14.0664C10.81 14.0664 11.3431 14.2139 11.7608 14.5285C12.2104 14.8627 12.4928 15.3954 12.4928 16.1603C12.4928 16.9887 12.1911 17.5607 11.7733 17.9136C11.3175 18.2925 10.6236 18.4722 9.77598 18.4722C9.26834 18.4722 8.90869 18.4401 8.66473 18.408ZM15.6748 15.8905V16.6867H14.1203V18.4276H13.1376V14.0986H15.7838V14.9011H14.1203V15.8905H15.6748Z" fill="white"/>
                        </svg>
                        ${item.file_name}
                    </a>`;
            });

        if (parentBlock) {
            const videoembedFiles = parentBlock.querySelector('.js-video-embed-files') as HTMLElement;
            videoembedFiles.innerHTML = html;
        }
    }

    playVideoByClickInit() {
        const playVideoBtns = document.querySelectorAll('.js-play-video-btn');

        if (!playVideoBtns) return;

        const removeAllActiveBtns = () => {
            playVideoBtns.forEach((el) => el.classList.remove('playing-video'));
        };

        playVideoBtns.forEach((el) => {
            const button = el as HTMLButtonElement;
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                removeAllActiveBtns();
                button.classList.add('playing-video');

                this.loadDataAndPlayVideo(button);
            });
        });
    }

    saveVideoTimeData(videoContainer, videoParams, learninMaterialType) {
        if (!videoContainer && !videoParams && !learninMaterialType) return;

        const videoDuration = videoParams.duration;
        const videoPauseTime = videoParams.seconds;

        const programmId = videoContainer.dataset.video_container_id?.split('_')[0];
        const shortBlockId = videoContainer.dataset.video_container_id?.split('_')[1];
        const videoId = videoContainer.dataset.video_id;

        let viewed = false;
        if (videoParams.percent) {
            viewed = videoParams.percent >= 0.9;
        }
        // Paths to current programm and block
        const currentProgrammPath = this.profileData[learninMaterialType].programms[programmId];
        const currentBlockPath = currentProgrammPath?.blocks[shortBlockId];

        currentBlockPath.fullBlockId = videoContainer.dataset.video_container_id;

        currentBlockPath.videos[videoId] = {
            ...currentBlockPath.videos[videoId],
            videoDuration,
            videoPauseTime,
            isVideoViewed: viewed,
        };

        this.changeBlockStatus(currentBlockPath);
        this.changePassedBlocksCount(currentProgrammPath);

        this.fetchDataToBackend(this.profileData);

        this.visualUpdateProgressBar(videoContainer, learninMaterialType);
    }

    createProfileVideoData(learninMaterialType) {
        const mainContainer = document.querySelector(`#${learninMaterialType}`) as HTMLElement;
        if (!mainContainer) return;

        const playVideoBtns = mainContainer.querySelectorAll('.js-play-video-btn') as NodeList;

        // Set user id
        if (mainContainer && mainContainer.dataset.user_id) {
            this.profileData.userId = +mainContainer.dataset.user_id;
        }

        playVideoBtns &&
            playVideoBtns.forEach((el) => {
                const button = el as HTMLButtonElement;

                const programmId = button.dataset?.video_container_id?.split('_')[0];
                const blockId = button.dataset?.video_container_id?.split('_')[1];
                const videoId = button.dataset?.video_id;

                const videoDuration = button.dataset?.video_duration;
                const videoPauseTime = button.dataset?.video_pause_time;
                const videoIsViewed = button.dataset?.video_viewed;

                const blocksPassedCount = button.dataset?.passed_blocks_count;

                if (!programmId || !blockId || !videoId) return;

                // If no current programm create it in programms array
                if (!this.profileData[learninMaterialType].programms[programmId]) {
                    this.profileData[learninMaterialType].programms[programmId] = {
                        programmId: +programmId.split('-')[1] || null,
                        blocksPassed: blocksPassedCount,
                        blocks: {},
                    };
                }

                // If no current block create it in blocks array
                if (!this.profileData[learninMaterialType].programms[programmId].blocks[blockId]) {
                    const currentBlock = this.getCurrentBlock(programmId, blockId);

                    this.profileData[learninMaterialType].programms[programmId].blocks[blockId] = {
                        blockStatus: currentBlock?.dataset?.block_status || null,
                        videos: {},
                    };
                }

                // Paths to current programm and block
                const currentProgrammPath = this.profileData[learninMaterialType].programms[programmId];
                const currentBlockPath = currentProgrammPath?.blocks[blockId];

                currentBlockPath.videos[videoId] = {
                    videoId: videoId || null,
                    videoDuration: videoDuration || null,
                    videoPauseTime: videoPauseTime || null,
                    isVideoViewed: videoIsViewed || '',
                };
            });
    }

    changeBlockStatus(currentBlockObject) {
        if (!currentBlockObject) return;

        let status = '';
        const videosArray: videoData[] = Object.values(currentBlockObject.videos);

        const isBlockPassed = videosArray.every((video) => video.isVideoViewed);
        const isBlockNotPassed = videosArray.every((video) => !video.isVideoViewed);

        if (isBlockPassed) {
            status = 'passed';
        } else if (isBlockNotPassed) {
            status = 'not-passed';
        } else {
            status = 'in-progress';
        }
        // eslint-disable-next-line no-param-reassign
        currentBlockObject.blockStatus = status;

        this.visualUpdateBlockStatus(currentBlockObject.fullBlockId, status);
    }

    visualUpdateBlockStatus(blockContainerId, status) {
        const queryStr = `.js-programm-block[data-block_id="${blockContainerId}"]`;
        const blockElem = document.querySelector(queryStr) as HTMLElement;

        if (blockElem) {
            blockElem.dataset.block_status = status;
            const statusContainer = blockElem.querySelector('.js-block-status');
            if (statusContainer) {
                statusContainer.classList.remove('passed');
                statusContainer.classList.remove('not-passed');
                statusContainer.classList.remove('in-progress');

                statusContainer.classList.add(status);
            }
        }
    }

    visualUpdateProgressBar(videoContainer, learninMaterialType) {
        // Finding programm inforamtion containers
        const programmIdStr = videoContainer?.dataset.video_container_id.split('_')[0];
        const programmItem = document.querySelector(`[data-programm_id="${programmIdStr}"]`) as HTMLElement;
        const progressBarWrap = programmItem.querySelector('.js-progress-info') as HTMLElement;
        // Set path to programm in var
        if (!this.profileData[learninMaterialType].programms) return;
        const programmsData = this.profileData[learninMaterialType].programms;

        // Set path to block in var
        if (!programmsData[programmIdStr].blocks) return;
        const blocksData = programmsData[programmIdStr].blocks;

        // Get total blocks count and count of passed blocks
        const blocksCount = Object.keys(blocksData).length;
        const passedBlocksCount = programmsData[programmIdStr].blocksPassed;

        // Finding progress bar containers
        const passedBlocksSpan = progressBarWrap.querySelector('.js-passed-blocks-span') as HTMLElement;
        const progressBar = progressBarWrap.querySelector('.js-progress-bar') as HTMLElement;

        if (progressBar) {
            // Generate progress bar line "svg rect"
            progressBar.innerHTML = this.generateProgressBar(161, blocksCount, passedBlocksCount);
        }

        if (passedBlocksSpan) {
            passedBlocksSpan.innerText = `${passedBlocksCount}`;
        }
    }

    generateProgressBar(totalWidth, blocksCount, blocksPassed) {
        let blockWidth = 0;
        if (blocksCount !== 0) {
            blockWidth = totalWidth / blocksCount;
        }

        let svg = '';

        svg += `<rect width="${totalWidth}" height="5" transform="matrix(1 0 0 -1 0 5)" fill="#131614" />`;

        for (let i = 0; i < blocksPassed; i++) {
            const xPosition = i * blockWidth;
            svg += `<rect width="${blockWidth}" height="5" transform="matrix(1 0 0 -1 ${xPosition} 5)" fill="#53F07F" />`;
        }
        return svg;
    }

    changePassedBlocksCount(currentProgrammObject) {
        if (!currentProgrammObject) return;

        const blocksArray: blockData[] = Object.values(currentProgrammObject.blocks);

        const countOfPassedBlocks = blocksArray.filter((block) => block.blockStatus === 'passed');

        // eslint-disable-next-line no-param-reassign
        currentProgrammObject.blocksPassed = countOfPassedBlocks.length;
    }

    playNextVideo() {
        const playNextBtns = document.querySelectorAll('.js-next-video-btn');

        playNextBtns &&
            playNextBtns.forEach((el) => {
                const btn = el as HTMLButtonElement;

                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const videoBlock = btn.closest('.js-programm-block') as HTMLElement;

                    const playVideoBtns = videoBlock.querySelectorAll('.js-play-video-btn');
                    const playVideoBtn = videoBlock.querySelector('.js-play-video-btn.playing-video');
                    const nextPLayBtn = playVideoBtn?.nextSibling as HTMLButtonElement;

                    if (nextPLayBtn) {
                        playVideoBtns &&
                            playVideoBtns.forEach((el2) => {
                                const btn2 = el2 as HTMLButtonElement;
                                btn2.classList.remove('playing-video');
                            });

                        nextPLayBtn.classList.add('playing-video');
                        this.loadDataAndPlayVideo(nextPLayBtn);
                    }
                });
            });
    }

    fetchDataToBackend(profileDataParams) {
        if (!profileDataParams) return;

        // @ts-ignore
        fetch(`${var_from_php.ajax_url}?action=save_video_data`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(profileDataParams),
        });
    }

    getCurrentBlock(programmId, blockId) {
        const blockIdstring = `${programmId}_${blockId}`;
        const currentBlock = document.querySelector(`[data-block_id="${blockIdstring}"]`) as HTMLElement;

        return currentBlock || null;
    }

    addEventfetchUserDataForm() {
        const form = document.querySelector('.js-user-info-form') as HTMLFormElement;
        if (!form) return;

        form &&
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(form);

                // @ts-ignore
                fetch(`${var_from_php.ajax_url}?action=update_user_data`, {
                    method: 'POST',
                    body: formData,
                })
                    .then((res) => res.json())
                    .then((res) => {
                        const respContainer = form.querySelector('.js-response-container');
                        const additionalClass = res.success ? 'success' : 'error';

                        if (respContainer) {
                            const paragrahp = document.createElement('p');
                            paragrahp.classList.add(additionalClass);
                            paragrahp.innerText = res.data;
                            respContainer.appendChild(paragrahp);
                        }
                    });
            });
    }

    editFormFieldAddEvent() {
        const editBtns = document.querySelectorAll('.js-edit-btn') as NodeList;
        const inpus = document.querySelectorAll('input[type="text"]') as NodeList;

        const setInputDefaultState = (el) => {
            const input = el as HTMLInputElement;

            input.classList.remove('focus');
        };

        editBtns &&
            editBtns.forEach((el) => {
                const btn = el as HTMLButtonElement;

                btn.addEventListener('click', (e) => {
                    e.preventDefault();

                    inpus && inpus.forEach((el2) => setInputDefaultState(el2));

                    const parentWrapper = btn.closest('.js-inner-input-wrapper');
                    const input = parentWrapper?.querySelector('input[type="text"]') as HTMLInputElement;

                    input.classList.add('focus');
                });
            });
    }

    // eslint-disable-next-line @typescript-eslint/no-empty-function
    initVimeoPlayer(playerEl, videoId, loadVideo = false, start = 0, cb = (pauseInfo) => {}) {
        let player;

        if (!loadVideo) {
            // If player was not initialized
            // @ts-ignore
            player = new Vimeo.Player(playerEl, {
                id: videoId,
            });

            start && player.setCurrentTime(start);
        } else {
            // If player already initialized
            player = playerEl;
            player.loadVideo(videoId).then(() => {
                start && player.setCurrentTime(start);
            });
        }

        // Reset "after pause" event
        player.off('pause', cb);
        player.on('pause', cb);

        return player;
    }

    initPlayerOnOpenBlock() {
        const blocks = document.querySelectorAll('.js-programm-block') as NodeList;

        blocks &&
            blocks.forEach((el) => {
                const block = el as HTMLElement;
                block.addEventListener('click', () => {
                    const player = block.querySelector(
                        `[data-video_container_id="${block.dataset.block_id}"]`
                    ) as HTMLElement;
                    // Get first button of block
                    const firstPlayBtnInBlcok = block.querySelector('.js-play-video-btn') as HTMLElement;
                    const videoStartTime = firstPlayBtnInBlcok?.dataset.video_pause_time
                        ? +firstPlayBtnInBlcok.dataset.video_pause_time
                        : 0;

                    if (!player || !firstPlayBtnInBlcok) return;

                    // Init vimeo player after open accrodiont block
                    firstPlayBtnInBlcok.classList.add('playing-video');

                    const onPauseCallback = (pauseInfo) => {
                        const parentProgramm = block.closest('.js-programm') as HTMLElement;
                        const playVideoBtn = block.querySelector(
                            `#video-btn-${player.dataset.video_id}`
                        ) as HTMLElement;

                        if (playVideoBtn) {
                            playVideoBtn.dataset.video_pause_time = `${pauseInfo.seconds}`;
                        }

                        this.saveVideoTimeData(player, pauseInfo, parentProgramm.id);
                    };

                    // @ts-ignore
                    this.initedPlayer = this.initVimeoPlayer(
                        player,
                        player.dataset.video_id,
                        false,
                        videoStartTime,
                        onPauseCallback
                    );
                });
            });
    }
}
