@if(session('game_ban'))
<div class="xvrx-ban-warning" style="background:rgba(240,173,78,.12);border-bottom:1px solid rgba(240,173,78,.3);padding:12px 24px;text-align:center;">
    <em class="icon ni ni-alert" style="color:#f0ad4e;margin-right:8px;vertical-align:middle;"></em>
    <span style="color:#f0ad4e;font-size:14px;vertical-align:middle;">{{ session('game_ban.reason') }}</span>
</div>
@endif
