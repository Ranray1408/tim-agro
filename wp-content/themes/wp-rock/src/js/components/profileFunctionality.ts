export class ProfileFunctionality {

    constructor() {
    }

    // Main init method
    init() {
        this.playVideoByClickInit();
    }

    playVideo(videoContainer, videoUrl) {
        if (!videoContainer || !videoUrl) return;

        const videoTags = document.querySelectorAll('video');
        const video = videoContainer.querySelector('video');

        videoTags && videoTags.forEach((el) => {
            const video = el as HTMLVideoElement;
            video.pause();
        });

        if (video) {
            video.src = videoUrl;
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
                button.classList.add('playing-video');
                const containerId = button.dataset?.video_container_id;
                const videoUrl = button.dataset?.video_url;
                const videoTitle = button.dataset?.video_title;

                const videoContainer = document.querySelector(`#${containerId}`);
                const videoTitleContainer = videoContainer?.querySelector('.js-video-title') as HTMLElement;

                if (videoTitleContainer) {
                    videoTitleContainer.innerHTML = `${videoTitle}`;
                }
                this.playVideo(videoContainer, videoUrl);
            })
        });
    }
}
