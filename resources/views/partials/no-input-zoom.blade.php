{{--
    iOS Safari kisi bhi field par focus hote hi page ko zoom kar deta hai agar us
    field ka font-size 16px se chhota ho. Zoom hone ke baad layout apni jagah se
    hat jata hai, isi liye mobile par har text field ko 16px par fix kiya gaya hai.

    Sirf wohi input types shamil hain jinme keyboard khulta hai — button, file,
    range, checkbox aur radio ko chhua nahi gaya taake unki dikhawat na badle.
--}}
<style>
    html {
        -webkit-text-size-adjust: 100%;
        text-size-adjust: 100%;
    }

    @media (max-width: 768px) {
        input:not([type]),
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="number"],
        input[type="tel"],
        input[type="url"],
        input[type="search"],
        input[type="date"],
        input[type="datetime-local"],
        input[type="month"],
        input[type="week"],
        input[type="time"],
        textarea,
        select {
            font-size: 16px !important;
        }
    }
</style>
