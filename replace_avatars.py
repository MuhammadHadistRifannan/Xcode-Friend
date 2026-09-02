import re
import glob

files = glob.glob('resources/views/**/*.blade.php', recursive=True) + glob.glob('app/Http/Controllers/**/*.php', recursive=True)

for f in files:
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    if 'ui-avatars.com' not in content:
        continue
        
    # Replace in blade files where it's concatenated up to }} or \" 
    # Example: 'https://ui-avatars.com/api/?name='.urlencode($stream->user->fullname ?? 'Unknown').'&background=E5E5E5' }}
    # We replace from 'https://ui-avatars.com to the closing }} with asset('assets/img/default.png') }}
    # Be careful not to replace things outside the blade tag.
    # A safe pattern:
    # re.sub(r"'https://ui-avatars\.com[^}]+}}", "asset('assets/img/default.png') }}", content)
    
    new_content = re.sub(r"'https://ui-avatars\.com[^}]+}}", r"asset('assets/img/default.png') }}", content)
    
    # What about in PHP controllers? 
    # 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->fullname).'&background=E5E5E5'
    # It ends with ' or maybe we can just replace everything up to the next quotation mark or comma.
    # Actually, in PHP controllers, we can just replace the whole ternary or fallback.
    # Let's just use a more generic regex for any ui-avatars.com concatenation chain.
    # A concatenation chain in PHP starting with 'https://ui-avatars.com' consists of strings and function calls.
    # Example: 'https://ui-avatars.com/api/?name='.urlencode($user->fullname).'&background=E5E5E5&size=128'
    new_content = re.sub(r"'https://ui-avatars\.com[^'\"]*?'\s*\.\s*urlencode\([^)]+\)(?:\s*\.\s*'[^'\"]*?')?", "asset('assets/img/default.png')", new_content)

    # For guest.blade.php onerror
    new_content = re.sub(r"onerror=\"this\.src='https://ui-avatars\.com[^\"]+\"", r"onerror=\"this.src='{{ asset('assets/img/default.png') }}'\"", new_content)
    
    with open(f, 'w', encoding='utf-8') as file:
        file.write(new_content)
    print(f'Updated {f}')
