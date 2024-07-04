export class ProfileFunctionality {
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
    constructor() { }

    // Main init method
    init() {
        this.playVideoByClickInit();
        this.createProfileVideoData('courses');
        //this.createProfileVideoData('lectures');
        this.playNextVideo();
        // User info form
        this.editFormFieldAddEvent();
        this.addEventfetchUserDataForm();

        this.initPlayerOnOpenBlock();
    }

    //Loading video data by click
    loadDataAndPlayVideo(playBtnData) {
        if (!playBtnData) return;

        const containerId = playBtnData.dataset?.video_container_id;
        const videoTitle = playBtnData.dataset?.video_title;
        const videoId = playBtnData.dataset?.video_id;
        const videoPauseTime = parseFloat(playBtnData.dataset?.video_pause_time);

        const videoContainer = document.querySelector(`[data-video_container_id="${containerId}"]`) as HTMLElement;
        const blockVideoWrapper = videoContainer.closest('.js-block-video') as HTMLElement;
        const videoTitleContainer = blockVideoWrapper?.querySelector('.js-video-title') as HTMLElement;

        if (videoTitleContainer) {
            videoTitleContainer.innerHTML = `${videoTitle}`;
        }

        if (!videoContainer) return;
        videoContainer.dataset.video_id = playBtnData.dataset.video_id;

        const onPauseCallback = (pauseInfo) => {
            playBtnData.dataset.video_pause_time = pauseInfo.seconds;
        }

        //@ts-ignore
        this.initVimeoPlayer(this.initedPlayer, videoId, true, videoPauseTime, onPauseCallback);
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

        console.log('related profileData', this.profileData);

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

        console.log('created profileData', this.profileData);
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
        currentBlockObject.blockStatus = status;

        this.visualUpdateBlockStatus(currentBlockObject.fullBlockId, status);
    }

    visualUpdateBlockStatus(blockContainerId, status) {
        const queryStr = `.js-programm-block[data-block_id="${blockContainerId}"]`;
        const blockElem = document.querySelector(queryStr) as HTMLElement;

        if (blockElem) {
            console.log('blockElem', blockElem);
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

        //Get total blocks count and count of passed blocks
        const blocksCount = Object.keys(blocksData).length
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

        svg += '<rect width="' + totalWidth + '" height="5" transform="matrix(1 0 0 -1 0 5)" fill="#131614" />';

        for (let i = 0; i < blocksPassed; i++) {
            const xPosition = i * blockWidth;
            svg += '<rect width="' + blockWidth + '" height="5" transform="matrix(1 0 0 -1 ' + xPosition + ' 5)" fill="#53F07F" />';
        }
        return svg;
    }

    changePassedBlocksCount(currentProgrammObject) {
        if (!currentProgrammObject) return;

        const blocksArray: blockData[] = Object.values(currentProgrammObject.blocks);

        const countOfPassedBlocks = blocksArray.filter((block) => block.blockStatus === 'passed');

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
                            playVideoBtns.forEach((el) => {
                                const btn = el as HTMLButtonElement;
                                btn.classList.remove('playing-video');
                            });

                        nextPLayBtn.classList.add('playing-video');
                        this.loadDataAndPlayVideo(nextPLayBtn);
                    }
                });
            });
    }

    fetchDataToBackend(profileData) {
        if (!profileData) return;

        // @ts-ignore
        fetch(`${var_from_php.ajax_url}?action=save_video_data`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(profileData),
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
                        console.log(res.data);
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

                    inpus && inpus.forEach((el) => setInputDefaultState(el));

                    const parentWrapper = btn.closest('.js-inner-input-wrapper');
                    const input = parentWrapper?.querySelector('input[type="text"]') as HTMLInputElement;

                    input.classList.add('focus');
                });
            });
    }

    initVimeoPlayer(playerEl, videoId, loadVideo = false, start = 0, cb = () => { }) {
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

        blocks && blocks.forEach((el) => {
            const block = el as HTMLElement;
            block.addEventListener('click', () => {

                const player = block.querySelector(`[data-video_container_id="${block.dataset.block_id}"]`) as HTMLElement;
                //Get first button of block
                const firstPlayBtnInBlcok = block.querySelector('.js-play-video-btn') as HTMLElement;
                const videoStartTime = firstPlayBtnInBlcok?.dataset.video_pause_time ? +firstPlayBtnInBlcok.dataset.video_pause_time : 0;

                if (!player || !firstPlayBtnInBlcok) return;

                // Init vimeo player after open accrodiont block
                firstPlayBtnInBlcok.classList.add('playing-video');

                const onPauseCallback = (pauseInfo) => {
                    const parentProgramm = block.closest('.js-programm') as HTMLElement;
                    const playVideoBtn = block.querySelector(`#video-btn-${player.dataset.video_id}`) as HTMLElement;

                    if (playVideoBtn) {
                        playVideoBtn.dataset.video_pause_time = `${pauseInfo.seconds}`;
                    }

                    this.saveVideoTimeData(player, pauseInfo, parentProgramm.id);
                }

                //@ts-ignore
                this.initedPlayer = this.initVimeoPlayer(player, player.dataset.video_id, false, videoStartTime, onPauseCallback);

            });
        });
    }
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

interface blockData {
    blockStatus: string | null;
    blockId: string;
    // Programm Vlock Video
    videos: {
        [key: string]: videoData;
    };
}

interface videoData {
    videoTitle: string | null;
    videoId: string | null;
    videoDuration: number | null;
    videoPauseTime: number | null;
    isVideoViewed: boolean;
}
