<h1>{$posts.0.category}</h1><br>
{foreach $posts as $postitem=>$postinfo}
<a onClick="viewPost('{$postinfo.ID}')">
<h2>{$postinfo.title}</h2>
</a>
<h3>{$postinfo.date|date_format:"%d/%m/%y"}</h3>
<p>{$postinfo.contents|truncate:40:".."}</p></br>
{/foreach}