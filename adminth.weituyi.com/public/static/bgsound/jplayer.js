
//<![CDATA[
$(document).ready(function(){
    //run_sound("error");
});
function run_sound(sound_tag){
    var sound_url = "";
    switch(sound_tag)
    {
        case "new_order"://新订单
            sound_url = FIEL_NEW_ORDER_MP3;
            break;
        case "error"://error.wav 这个是查询不到的话会有的显示,
            sound_url = FIEL_ERROR_MP3;
            break;
        case "hc_error"://提错误提示
            sound_url = FIEL_HC_ERROR_MP3;
            break;
        case "right"://在输入包裹单号的地方扫描输入有查询信息的时候就自动播放一次right.wav
            sound_url = FIEL_RIGHT_MP3;
            break;
        default:


    }
    $("#jquery_jplayer_1").jPlayer("destroy");//销毁
    $("#jquery_jplayer_1").jPlayer({
        ready: function (event) {
            $(this).jPlayer("setMedia", {
                title: "Bubble",
                //m4a: "http://jplayer.org/audio/m4a/Miaow-07-Bubble.m4a",
                //oga: "http://jplayer.org/audio/ogg/Miaow-07-Bubble.ogg"
                mp3: sound_url
            }).jPlayer("play");
        },
        swfPath: JPLAYER_SWF_PATH,//"../../dist/jplayer",
        //supplied: "m4a, oga",
        supplied: "mp3",
        wmode: "window",
        useStateClassSkin: true,
        autoBlur: false,
        smoothPlayBar: true,
        keyEnabled: true,
        remainingDuration: true,
        toggleDuration: true
    });
}
//]]>

(function() {
    document.write("<input type=\"button\" value=\"操作成功\" onclick=\"run_sound(\'right\')\">");
    document.write("<input type=\"button\" value=\"错误提示\" onclick=\"run_sound(\'hc_error\')\">");
    document.write("<input type=\"button\" value=\"查询不到\" onclick=\"run_sound(\'error\')\">");
    document.write("<input type=\"button\" value=\"新订单\" onclick=\"run_sound(\'new_order\')\">");
    document.write("<div id=\"jquery_jplayer_1\" class=\"jp-jplayer\"><\/div>");
    document.write("<div id=\"jp_container_1\" class=\"jp-audio\" role=\"application\" aria-label=\"media player\">");
    document.write("    <div class=\"jp-type-single\">");
    document.write("        <div class=\"jp-gui jp-interface\">");
    document.write("            <div class=\"jp-controls\">");
    document.write("                <button class=\"jp-play\" role=\"button\" tabindex=\"0\">play<\/button>");
    document.write("                <button class=\"jp-stop\" role=\"button\" tabindex=\"0\">stop<\/button>");
    document.write("            <\/div>");
    document.write("            <div class=\"jp-progress\">");
    document.write("                <div class=\"jp-seek-bar\">");
    document.write("                    <div class=\"jp-play-bar\"><\/div>");
    document.write("                <\/div>");
    document.write("            <\/div>");
    document.write("            <div class=\"jp-volume-controls\">");
    document.write("                <button class=\"jp-mute\" role=\"button\" tabindex=\"0\">mute<\/button>");
    document.write("                <button class=\"jp-volume-max\" role=\"button\" tabindex=\"0\">max volume<\/button>");
    document.write("                <div class=\"jp-volume-bar\">");
    document.write("                    <div class=\"jp-volume-bar-value\"><\/div>");
    document.write("                <\/div>");
    document.write("            <\/div>");
    document.write("            <div class=\"jp-time-holder\">");
    document.write("                <div class=\"jp-current-time\" role=\"timer\" aria-label=\"time\">&nbsp;<\/div>");
    document.write("                <div class=\"jp-duration\" role=\"timer\" aria-label=\"duration\">&nbsp;<\/div>");
    document.write("                <div class=\"jp-toggles\">");
    document.write("                    <button class=\"jp-repeat\" role=\"button\" tabindex=\"0\">repeat<\/button>");
    document.write("                <\/div>");
    document.write("            <\/div>");
    document.write("        <\/div>");
    document.write("        <div class=\"jp-details\">");
    document.write("            <div class=\"jp-title\" aria-label=\"title\">&nbsp;<\/div>");
    document.write("        <\/div>");
    document.write("        <div class=\"jp-no-solution\">");
    document.write("            <span>Update Required<\/span>");
    document.write("            To play the media you will need to either update your browser to a recent version or update your <a href=\"http:\/\/get.adobe.com\/flashplayer\/\" target=\"_blank\">Flash plugin<\/a>.");
    document.write("        <\/div>");
    document.write("    <\/div>");
    document.write("<\/div>");
}).call();