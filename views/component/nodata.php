$(
$TK["nodata_content"] = !isset($TK["nodata_content"]) ? "暂无数据!" : $TK["nodata_content"];


)$
<style>
    .nodata {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;

        img {
            width: 40%;
        }
        p{
            font-size: 1.6vw;
        }
    }
</style>
[Start]

<div class="nodata">
    <img src="IMAGEURL/CD.png" alt="">
    <p>$-TK["nodata_content"]-$</p>
</div>

[/Start]