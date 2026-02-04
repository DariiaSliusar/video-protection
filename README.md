# Video Protection Laravel Project

This project implements a secure video playback mechanism based on the Best Effort Protection principle. The system prevents direct downloading of video files and restricts access to content to authorized sessions only.

---

**Best Effort Protection** is an approach where the system does everything possible to complicate unauthorized access to video, but does not guarantee absolute protection against attackers. This project uses the following mechanisms:

- The video is split into HLS segments (.ts), making it impossible to simply download via the browser.
- All media files are stored in private storage (`storage/app/private`), which is not directly accessible.
- Access to the video is granted only through a session, which is created automatically when visiting the main page.
- Links to segments are generated dynamically and cryptographically signed, limiting their validity period.

---

## Key Features

- **HLS Segmentation**: The video is not streamed as a single file, but split into small segments (.ts), making standard downloading via browser impossible.
- **Private Storage**: All media files are stored in protected storage (`storage/app/private`) with no public access.
- **Session-Based Access**: Access to the video is automatically granted via session when visiting the main page.
- **Dynamic Playlist & Signed URLs**: Links to each video segment are generated dynamically and cryptographically signed.
- **Modern UI**: Tailwind CSS, Blade components.

## Deployment Instructions

### 1. Clone the repository

```bash
git clone https://github.com/DariiaSliusar/video-protection.git
cd video-protection
```

### 2. Install dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Create directory for video

Laravel does not track files in storage/app/private, so you need to create the folder manually:

```bash
mkdir -p storage/app/private/videos/test-video
```

### 5. Add video files

Place your playlist file (`playlist.m3u8`) and segments (`.ts`) in the `storage/app/private/videos/test-video/` folder.

*If you do not have ready HLS files, use the FFmpeg command from the section below.*

### 6. Start the server

```bash
php artisan serve
```

or to run on another port (e.g., 8005):

```bash
php artisan serve --port=8005
```

### 7. View

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) or [http://127.0.0.1:8005](http://127.0.0.1:8005) in your browser. Access to the video will be granted automatically via session creation.

---

## How to prepare video (FFmpeg)

If you have a `video.mp4` file, convert it to HLS for use with the project:

```bash
ffmpeg -i video.mp4 -c:v copy -c:a copy -f hls -hls_time 10 -hls_playlist_type vod storage/app/private/videos/test-video/playlist.m3u8
```

---

## License

This project is open-sourced under the [MIT license](LICENSE).
