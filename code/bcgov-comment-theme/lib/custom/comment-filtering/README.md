<h2>Comment Filtering README</h2>
<p><strong>Version 1.0.0</strong></p>

<h4>Features</h4>
<ul>
    <li>Bootstrap Datepicker via https://eternicode.github.io/bootstrap-datepicker/</li>
    <li>Allows search by term (searches both authors and comment_content)</li>
    <li>Search by custom range of time (ranging from publication of post to end of current WP day)</li>
    <li>Search by preset range of time (today, one week, one month)</li>
    <li>Uses the current_time of WP install for searches, not server time (avoids inconsistencies in time zones)</li>
</ul>

<h4>Features available if desired</h4>
<ul>
    <li>Combining search term and date searches</li>
</ul>

<h4>To Do</h4>
<ul>
    <li>Front and back end validation</li>
    <li>Only enqueue on appropriate pages? (any kind of post, page, or post type)</li>
    <li>Include information about the library I'm using</li>
    <li>Add 'all time' to dropdown</li>
</ul>