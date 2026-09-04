<?php
$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/home/beranda.blade.php';
$content = file_get_contents($file);

$search = <<<'EOD'
                            <p class="text-[11px] text-neutral-400">{{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}</p>
                        </div>
                        
                        <!-- 3-dots dropdown -->
EOD;

$replace = <<<'EOD'
                            <p class="text-[11px] text-neutral-400">{{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}</p>
                        </div>
                        </div>
                        <!-- 3-dots dropdown -->
EOD;

$content = str_replace($search, $replace, $content);
file_put_contents($file, $content);
echo "Added missing div closing tag in beranda.\n";
