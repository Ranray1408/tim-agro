export class ProfileFunctionality {
    profileData: profileData = {
        programmType: '',
        userId: 0,
        programms : {}
    };

    constructor() { }

    // Main init method
    init() {
        this.playVideoByClickInit();
        this.addPauseListenerToAllVideos();
        this.createProfileVideoData();
        this.playNextVideo();
    }

    loadDataAndPlayVideo(playBtnData) {
        if (!playBtnData) return;

        const containerId = playBtnData.dataset?.video_container_id;
        const videoUrl = playBtnData.dataset?.video_url;
        const videoTitle = playBtnData.dataset?.video_title;
        const videoId = playBtnData.dataset?.video_id;
        const videoPlayingByBtn = playBtnData.dataset?.play_btn_id;

        const videoContainer = document.querySelector(`#${containerId}`);
        const videoTitleContainer = videoContainer?.querySelector('.js-video-title') as HTMLElement;

        if (videoTitleContainer) {
            videoTitleContainer.innerHTML = `${videoTitle}`;
        }

        if (!videoContainer) return;

        const video = videoContainer.querySelector('video');

        this.pauseAllVideos();

        if (video) {
            video.src = videoUrl;
            video.dataset.video_id = videoId;
            video.dataset.video_playing_by_btn = videoPlayingByBtn;
            video.play();
        }
    }

    playVideoByClickInit() {
        const playVideoBtns = document.querySelectorAll('.js-play-video-btn');

        if (!playVideoBtns) return;

        const removeAllActiveBtns = () => {
            playVideoBtns.forEach(el => el.classList.remove('playing-video'));
        }

        playVideoBtns.forEach((el) => {
            const button = el as HTMLButtonElement;
            button.addEventListener('click', (e) => {
                removeAllActiveBtns();
                this.pauseAllVideos();
                button.classList.add('playing-video');

                this.loadDataAndPlayVideo(button);
            })
        });
    }

    addPauseListenerToAllVideos = () => {
        const videos = document.querySelectorAll('video') as NodeList;

        videos && videos.forEach((el) => {
            const video = el as HTMLVideoElement;

            video.addEventListener('pause', () => {
                this.saveVideoTimeData(video);
            });
        });
    }

    saveVideoTimeData(video) {
        if (!video.dataset?.video_id) return;

        const videoDuration = video.duration;
        const videoPuseTime = video.currentTime;
        let viewed = false;

        const programmId = video.dataset.video_id?.split('_')[0];
        const blockId = video.dataset.video_id?.split('_')[1];
        const videoId = video.dataset.video_id?.split('_')[2];

        if (videoPuseTime && videoDuration) {
            viewed = +videoPuseTime / +videoDuration >= 0.9;
        }

        // Paths to current programm and block
        const currentProgrammPath = this.profileData.programms[programmId];
        const currentBlockPath = currentProgrammPath.blocks[blockId];

        currentBlockPath.videos[videoId] = {
            ...currentBlockPath.videos[videoId],
            videoDuration: videoDuration,
            videoPauseTime: videoPuseTime,
            isVideoViewed: viewed,
        };

        this.changeBlockStatus(currentBlockPath);
        this.changeprogrammBlocksPassedStatus(currentProgrammPath);

        console.log('reated profileData', this.profileData);
        this.fetchDataToBackend(this.profileData);
    }

    createProfileVideoData() {
        const playVideoBtns = document.querySelectorAll('.js-play-video-btn') as NodeList;
        const panelPragromm = document.querySelector('.js-panel-programm') as HTMLElement;

        if (panelPragromm && panelPragromm.dataset.user_id) {
            this.profileData.userId = +panelPragromm.dataset.user_id;
        }

        if (panelPragromm && panelPragromm.dataset.programm_type) {
            this.profileData.programmType = panelPragromm.dataset.programm_type;
        }

        playVideoBtns && playVideoBtns.forEach((el) => {
            const button = el as HTMLButtonElement;

            const programmId = button.dataset?.video_id?.split('_')[0];
            const blockId = button.dataset?.video_id?.split('_')[1];
            const videoId = button.dataset.video_id?.split('_')[2];
            const videoTitle = button.dataset.video_title

            if (!programmId || !blockId || !videoId) return;

            if (!this.profileData.programms[programmId]) {
                this.profileData.programms[programmId] = {
                    programmId: +programmId.split('-')[1] || null,
                    blocksPassed: 0,
                    blocks: {}
                };
            }

            if (!this.profileData.programms[programmId].blocks[blockId]) {

                const currentBlock = this.getCurrentBlock(programmId, blockId);

                this.profileData.programms[programmId].blocks[blockId] = {
                    blockStatus: currentBlock?.dataset?.block_status || null,
                    videos: {}
                };
            }

            // Paths to current programm and block
            let currentProgrammPath = this.profileData.programms[programmId];
            const currentBlockPath = currentProgrammPath.blocks[blockId];

            currentBlockPath.videos[videoId] = {
                videoTitle: videoTitle || null,
                videoId: videoId || null,
                videoDuration: null,
                videoPauseTime: null,
                isVideoViewed: false,
            };
        });

        console.log('reated profileData', this.profileData);
    }

    pauseAllVideos() {
        const videos = document.querySelectorAll('video') as NodeList;

        videos && videos.forEach((el) => {
            const video = el as HTMLVideoElement;

            video.pause();
        });
    }

    changeBlockStatus(currentBlockObject) {
        if (!currentBlockObject) return;

        const videosArray: videoData[] = Object.values(currentBlockObject.videos);

        let isBlockPassed = videosArray.every(video => video.isVideoViewed);
        let isBlockNotPassed = videosArray.every(video => !video.isVideoViewed);

        if (isBlockPassed) {
            currentBlockObject.blockStatus = 'passed';
        } else if (isBlockNotPassed) {
            currentBlockObject.blockStatus = 'not-passed';
        } else {
            currentBlockObject.blockStatus = 'in-progress';
        }
    }

    changeprogrammBlocksPassedStatus(currentProgrammObject) {
        if (!currentProgrammObject) return;

        const blocksPassedArray: blockData[] = Object.values(currentProgrammObject.blocks);
        console.log('blocksPassedArray', blocksPassedArray);

        const countOfPassedBlocks = blocksPassedArray.filter(block => block.blockStatus === 'passed');

        currentProgrammObject.blocksPassed = countOfPassedBlocks.length;
    }

    playNextVideo() {
        const playNextBtns = document.querySelectorAll('.js-next-video-btn');

        playNextBtns && playNextBtns.forEach((el) => {
            const btn = el as HTMLButtonElement;

            btn.addEventListener('click', () => {
                const videoBlock = btn.closest('.js-programm-block') as HTMLElement;

                const playVideoBtns = videoBlock.querySelectorAll('.js-play-video-btn');
                const playVideoBtn = videoBlock.querySelector('.js-play-video-btn.playing-video');
                const nextPLayBtn = playVideoBtn?.nextSibling as HTMLButtonElement;

                if (nextPLayBtn) {
                    playVideoBtns && playVideoBtns.forEach((el) => {
                        const btn = el as HTMLButtonElement;
                        btn.classList.remove('playing-video');
                    });

                    this.loadDataAndPlayVideo(nextPLayBtn);
                    nextPLayBtn.classList.add('playing-video');
                }
            });
        })
    }

    fetchDataToBackend(profileData) {
        if (!profileData) return;

        //@ts-ignore
        fetch(`${var_from_php.ajax_url}?action=save_video_data`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(profileData)
        });
    }

    getCurrentBlock(programmId, blockId) {
        const blockIdstring = `${programmId}_${blockId}-container`;
        const currentBlock = document.querySelector(`[data-block_id="${blockIdstring}"]`) as HTMLElement;

        return currentBlock || null;
    }

    fetchUserDataForm() {
        const form = document.querySelector('.js-user-info-form') as HTMLFormElement;

        form && form.addEventListener('submit', () => {
            const formData = new FormData(form);

            //@ts-ignore
            fetch(`${var_from_php.ajax_url}?action=update_user_data`, {
                method: 'POST',
                body: formData
            });
        });
    }
}

interface profileData {
    userId: number
    programmType: string,
    programms: {
        //Programm
        [key: string]: {
            programmId: number | null,
            blocksPassed: number | null,
            blocks: {
                //Programm Block
                [key: string]: blockData
            }
        }
    }
}

interface blockData {
    blockStatus: string | null,
    // Programm Vlock Video
    videos: {
        [key: string]: videoData
    }
}

interface videoData {
    videoTitle: string | null,
    videoId: string | null,
    videoDuration: number | null,
    videoPauseTime: number | null,
    isVideoViewed: boolean,
}
