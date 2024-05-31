export class ProfileFunctionality {
    profileData: profileData = {};

    constructor() { }

    // Main init method
    init() {
        this.playVideoByClickInit();
        this.addPauseListenerToAllVideos();
        this.createProfileVideoData();
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

        const Programm = this.profileData[programmId];
        const programmBlock = this.profileData[programmId].blocks[blockId];

        programmBlock.videos[videoId] = {
            ...this.profileData[programmId].blocks[blockId].videos[videoId],
            videoDuration: videoDuration,
            videoPauseTime: videoPuseTime,
            isVideoViewed: viewed,
        };

        this.changeBlockStatus(programmBlock);
        this.changeprogrammBlocksPassedStatus(Programm);

        console.log('reated profileData', this.profileData);
    }

    createProfileVideoData() {
        const playVideoBtns = document.querySelectorAll('.js-play-video-btn') as NodeList;

        playVideoBtns && playVideoBtns.forEach((el) => {
            const button = el as HTMLButtonElement;

            const programmId = button.dataset?.video_id?.split('_')[0];
            const blockId = button.dataset?.video_id?.split('_')[1];
            const videoId = button.dataset.video_id?.split('_')[2];
            const videoTitle = button.dataset.video_title

            if (!programmId || !blockId || !videoId) return;

            if (!this.profileData[programmId]) {
                this.profileData[programmId] = {
                    blocksPassed: 0,
                    blocks: {}
                };
            }

            if (!this.profileData[programmId].blocks[blockId]) {
                this.profileData[programmId].blocks[blockId] = {
                    blockStatus: 'not-passed',
                    videos: {}
                };
            }

            if (!this.profileData[programmId].blocks?.[blockId]?.videos) return;

            const programmBlock = this.profileData[programmId].blocks[blockId];

            programmBlock.videos[videoId] = {
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
        console.log(currentBlockObject);

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

        const countOfPassedBlocks = blocksPassedArray.filter(block => block.blockStatus === 'passed');

        currentProgrammObject.blocksPassed = countOfPassedBlocks.length;
    }
}

interface profileData {
    //Programm
    [key: string]: {
        blocksPassed: number | null,
        blocks: {
            //Programm Block
            [key: string]: blockData
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
