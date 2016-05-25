<h1>{$post.title}</h1><br>
<h3>{$post.date|date_format:"%d/%m/%y"}</h3>
<p>{$post.contents}</p>
<br>
>> <a onClick="viewPosts('{$post.category}')">Alle {$post.category} berichten</a>