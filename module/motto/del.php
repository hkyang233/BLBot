<?php

global $Event;

replyAndLeave(delMotto($Event['user_id']) ? '删除个性签名成功～' : '暂未设置个性签名哦…');