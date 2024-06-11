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

    constructor() {}

    // Main init method
    init() {
        this.playVideoByClickInit();
        this.addPauseListenerToAllVideos();
        this.createProfileVideoData('courses');
        this.createProfileVideoData('lectures');
        this.playNextVideo();
        // User info form
        this.editFormFieldAddEvent();
        this.addEventfetchUserDataForm();
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
            playVideoBtns.forEach((el) => el.classList.remove('playing-video'));
        };

        playVideoBtns.forEach((el) => {
            const button = el as HTMLButtonElement;
            button.addEventListener('click', (e) => {
                removeAllActiveBtns();
                this.pauseAllVideos();
                button.classList.add('playing-video');

                this.loadDataAndPlayVideo(button);
            });
        });
    }

    addPauseListenerToAllVideos = () => {
        const videos = document.querySelectorAll('video') as NodeList;

        videos &&
            videos.forEach((el) => {
                const video = el as HTMLVideoElement;

                video.addEventListener('pause', () => {
                    const parentPanel = video.closest(`.js-tab-panel`) as HTMLElement;

                    this.saveVideoTimeData(video, parentPanel.id);
                });
            });
    };

    saveVideoTimeData(video, learninMaterialType) {
        if (!video.dataset?.video_id) return;

        // Videio data
        const videoDuration = video.duration;
        const videoPauseTime = video.currentTime;
        const programmId = video.dataset.video_id?.split('_')[0];
        const blockId = video.dataset.video_id?.split('_')[1];
        const videoId = video.dataset.video_id?.split('_')[2];

        let viewed = false;
        if (videoPauseTime && videoDuration) {
            viewed = +videoPauseTime / +videoDuration >= 0.9;
        }

        // Paths to current programm and block
        const currentProgrammPath = this.profileData[learninMaterialType].programms[programmId];
        const currentBlockPath = currentProgrammPath?.blocks[blockId];

        currentBlockPath.videos[videoId] = {
            ...currentBlockPath.videos[videoId],
            videoDuration,
            videoPauseTime,
            isVideoViewed: viewed,
        };

        this.changeBlockStatus(currentBlockPath);
        this.changePassedBlocksCount(currentProgrammPath);

        console.log('reated profileData', this.profileData);
        this.fetchDataToBackend(this.profileData);
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

                const programmId = button.dataset?.video_id?.split('_')[0];
                const blockId = button.dataset?.video_id?.split('_')[1];
                const videoId = button.dataset.video_id?.split('_')[2];
                const videoTitle = button.dataset.video_title;

                const videoDuration = button.dataset?.video_duration;
                const videoPauseTime = button.dataset?.video_pause_time;
                const videoIsViewed = button.dataset?.video_viewed;

                const blocksPassedCount = button.dataset?.passed_blocks_count;

                if (!programmId || !blockId || !videoId) return;

                if (!this.profileData[learninMaterialType].programms[programmId]) {
                    this.profileData[learninMaterialType].programms[programmId] = {
                        programmId: +programmId.split('-')[1] || null,
                        blocksPassed: blocksPassedCount,
                        blocks: {},
                    };
                }

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
                    videoTitle: videoTitle || null,
                    videoId: videoId || null,
                    videoDuration: videoDuration || null,
                    videoPauseTime: videoPauseTime || null,
                    isVideoViewed: videoIsViewed || '',
                };
            });

        console.log('created profileData', this.profileData);
    }

    pauseAllVideos() {
        const videos = document.querySelectorAll('video') as NodeList;

        videos &&
            videos.forEach((el) => {
                const video = el as HTMLVideoElement;

                video.pause();
            });
    }

    changeBlockStatus(currentBlockObject) {
        if (!currentBlockObject) return;

        const videosArray: videoData[] = Object.values(currentBlockObject.videos);

        const isBlockPassed = videosArray.every((video) => video.isVideoViewed);
        const isBlockNotPassed = videosArray.every((video) => !video.isVideoViewed);

        if (isBlockPassed) {
            currentBlockObject.blockStatus = 'passed';
        } else if (isBlockNotPassed) {
            currentBlockObject.blockStatus = 'not-passed';
        } else {
            currentBlockObject.blockStatus = 'in-progress';
        }
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

                btn.addEventListener('click', () => {
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

                        this.loadDataAndPlayVideo(nextPLayBtn);
                        nextPLayBtn.classList.add('playing-video');
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
        const blockIdstring = `${programmId}_${blockId}-container`;
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
                        const respContainer = document.querySelector('.js-response-container');
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
