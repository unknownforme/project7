<div class="inside_space colored spaced">gevangenen<a href="prisoners">view</a></div>
<div class="inside_space colored spaced">cellen<a href="cells">view</a></div>
<div class="inside_space colored spaced">geschiedenis<a href="history">view</a></div>
<?php if($this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)): ?>
    <div class="inside_space colored spaced">accountbeheer<a href="users">view</a></div>
<?php endif; ?>