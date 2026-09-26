# libPersianText

**Persian (Farsi) text that actually reads right in Minecraft Bedrock — for PocketMine-MP 5.**

Bedrock has no Arabic-script shaping and no right-to-left support, so Persian sent from a plugin shows up as
disconnected letters in reverse order. `Persian::fix()` shapes the letters (joined initial / medial / final
forms, including the لا ligature) and reorders every line right-to-left, while keeping `§` color codes, English
words, numbers and commands readable. It works for chat messages, titles, forms, scoreboards — any string.

```php
use ApexGaming\libPersianText\Persian;

$player->sendMessage(Persian::fix("§aخوش آمدید! برای راهنما بزنید §e/help"));
```

Install it **once on your server** and every plugin can use it — no copying it into each plugin.

[فارسی ↓](#فارسی)

---

## Install

### As a plugin (recommended)

1. Download `libPersianText.phar` from [Releases](https://github.com/ApexMine/libPersianText/releases) and put it in
   your server's `plugins/` folder. (Or put this repository's folder there if you run
   [DevTools](https://poggit.pmmp.io/p/DevTools) for folder plugins.)
2. In every plugin that uses it, add the dependency to its `plugin.yml`:

   ```yaml
   depend: [libPersianText]
   ```

3. Use it — no copying, no namespace changes:

   ```php
   use ApexGaming\libPersianText\Persian;
   ```

This works because PocketMine shares the classes of every loaded plugin. `depend` makes sure libPersianText is
loaded first, and the server refuses to start your plugin with a clear error if libPersianText is missing.
libPersianText loads at `STARTUP`, so plugins with `load: STARTUP` can depend on it too.

### As a virion

If you'd rather ship the library inside your plugin: this repository is also a virion (`virion.yml`, antigen
`ApexGaming\libPersianText`). Put it in `virions/` with [DEVirion](https://poggit.pmmp.io/p/DEVirion) while
developing, and inject it into your plugin's phar when you build it. Don't also install the plugin version on the
same server unless the virion was injected (and so renamed) — two copies with the same namespace would clash.

### With Composer

```json
{
    "repositories": [{ "type": "vcs", "url": "https://github.com/ApexMine/libPersianText" }],
    "require": { "apexmine/libpersiantext": "^1.0" }
}
```

Requires PHP 8.1+ with `mbstring` (both come with PocketMine-MP 5). The text engine itself has no PocketMine
dependencies.

## Usage

| Method | What it does |
|---|---|
| `Persian::fix(string $text, int $maxLineLength = 0) : string` | Shapes and reorders the text. Text without Persian letters is returned unchanged. |
| `Persian::fixAll(array $lines, int $maxLineLength = 0) : array` | `fix()` on every string in a list. |
| `Persian::wrap(string $text, int $maxLength) : string` | Only the line wrapping, on logical (not yet fixed) text. |
| `Persian::setEnabled(bool $enabled) : void` | Global switch, e.g. when your plugin's language is set to English. |

Results are cached (up to 1024 strings), so calling `fix()` on the same text every tick is cheap.

```php
$form = new SimpleForm(function(Player $player, ?int $data) : void{ /* ... */ });
$form->setTitle(Persian::fix("فروشگاه"));
$form->setContent(Persian::fix("§eموجودی شما: §f{$balance}\n§7یک دسته را انتخاب کنید.", 40));
$form->addButton(Persian::fix("§aخرید"));
```

### Long lines: use `$maxLineLength`

When the client wraps a long right-to-left line itself, the pieces end up in the wrong order and the text has to be
read bottom-to-top. Pass a maximum line length and `fix()` splits long lines at word boundaries first:

```php
$form->setContent(Persian::fix($longText, 40)); // ~40 suits forms; chat can take more
```

Each new line keeps the active color, and text inside `()`, `[]`, `{}` or a run of English words such as
`/shop buy` is never split.

## Tips

- **Fix once, at the end.** Build the full string first, then call `fix()`. If you fix a word (a status, a tag)
  and then insert it into another string that you fix again, that word gets reversed twice.
- **The first word of a line is shown on the right.** To show a command on the left with its description on the
  right, write the description first: `"باز کردن فروشگاه : /shop"`.
- **Don't wrap English arguments in `<>`.** `"/shop <item>"` displays as `<item> /shop`, because an angle-bracket
  group is kept as its own block. Write `"/shop ItemName"` instead.
- **Give each part its own color.** A part without a color code of its own (a number after a colored `[tag]`,
  for example) takes the color of whatever is drawn before it. Start lines with a color and use `§f` where you
  want plain white.
- **Use the half-space (ZWNJ)** in words like می‌شود. It separates the letters correctly and isn't drawn in game.
- **Arabic letters work too** (أ إ ؤ ئ ة ي ك ى ۀ, and لأ لإ لآ). Marks above or below letters (tanween as in
  «قبلاً», fatha, kasra, shadda...) can't be drawn in Minecraft, so they are removed; «هٔ» becomes «ۀ».
- **Use English digits**, and write the word «درصد» instead of `٪`, which some fonts don't have.
- **If Persian shows as boxes only inside `ModalForm`s,** your resource pack probably doesn't style modal forms.
  Use a `SimpleForm` with two buttons for confirmations instead.

## Credits

Made by **Kevin** for the [ApexMine](https://github.com/ApexMine) server. Licensed under [MIT](LICENSE).

---

<div dir="rtl">

## فارسی

**متن فارسی درست و خوانا در ماینکرفت بدراک، برای PocketMine-MP 5.**

ماینکرفت بدراک از چسباندن حروف فارسی و راست‌به‌چپ پشتیبانی نمی‌کند؛ برای همین متن فارسی که یک پلاگین می‌فرستد با
حروف جدا از هم و برعکس نمایش داده می‌شود. `Persian::fix()` حروف را به شکل درست به هم می‌چسباند (شکل اول، وسط و آخر
کلمه و «لا») و هر خط را راست‌به‌چپ مرتب می‌کند. کدهای رنگ `§`، کلمه‌های انگلیسی، عددها و دستورها هم خوانا و سر جایشان
می‌مانند. برای پیام چت، تایتل، فرم، اسکوربورد و هر متن دیگری کار می‌کند.

کتابخانه را **یک بار روی سرور نصب می‌کنید** و همه پلاگین‌ها از آن استفاده می‌کنند؛ لازم نیست داخل هر پلاگین کپی شود.

### نصب به‌عنوان پلاگین (پیشنهادی)

1. فایل `libPersianText.phar` را از [Releases](https://github.com/ApexMine/libPersianText/releases) دانلود کنید و در
   پوشه `plugins/` سرور بگذارید. (اگر DevTools دارید، پوشه همین ریپو را هم می‌شود مستقیم گذاشت.)
2. در `plugin.yml` هر پلاگینی که از آن استفاده می‌کند این خط را اضافه کنید:

<div dir="ltr">

```yaml
depend: [libPersianText]
```

</div>

3. در کد پلاگین فقط `use` کنید؛ نه کپی لازم است نه تغییر namespace:

<div dir="ltr">

```php
use ApexGaming\libPersianText\Persian;

$player->sendMessage(Persian::fix("§aخوش آمدید! برای راهنما بزنید §e/help"));
```

</div>

این کار می‌کند چون PocketMine کلاس‌های همه پلاگین‌های لودشده را بین هم به اشتراک می‌گذارد. `depend` باعث می‌شود
libPersianText اول لود شود، و اگر روی سرور نصب نباشد، سرور با یک پیام واضح پلاگین شما را روشن نمی‌کند.
libPersianText در مرحله `STARTUP` لود می‌شود، پس پلاگین‌هایی که `load: STARTUP` دارند هم می‌توانند به آن وابسته باشند.

### روش‌های دیگر نصب

- **virion:** اگر می‌خواهید کتابخانه داخل خود پلاگین باشد، این ریپو یک virion هم هست (antigen:
  `ApexGaming\libPersianText`). موقع توسعه با DEVirion در پوشه `virions/` بگذارید و موقع ساخت phar آن را inject کنید.
  همزمان نسخه پلاگینی را روی همان سرور نصب نکنید، مگر اینکه virion inject شده باشد (و در نتیجه namespace آن عوض شده
  باشد)؛ دو نسخه با یک namespace با هم تداخل دارند.
- **Composer:** ریپو را به‌عنوان `vcs` اضافه کنید و `apexmine/libpersiantext` را require کنید (نمونه در بخش انگلیسی).

نیاز به PHP 8.1 به بالا و `mbstring` دارد که هر دو همراه PocketMine-MP 5 هستند.

### متدها

| متد | کار |
|---|---|
| `Persian::fix($text, $maxLineLength = 0)` | متن را شکل‌دهی و راست‌به‌چپ می‌کند. متن بدون حرف فارسی دست نمی‌خورد. |
| `Persian::fixAll($lines, $maxLineLength = 0)` | `fix()` روی همه متن‌های یک آرایه. |
| `Persian::wrap($text, $maxLength)` | فقط شکستن خط‌های بلند، روی متن اصلی (قبل از fix). |
| `Persian::setEnabled($enabled)` | خاموش و روشن کردن کلی، مثلا وقتی زبان پلاگین انگلیسی است. |

نتیجه‌ها کش می‌شوند (تا 1024 متن)، پس صدا زدن `fix()` روی یک متن ثابت در هر تیک هزینه‌ای ندارد.

### خط‌های بلند

اگر خط راست‌به‌چپ بلند باشد و خود بازی آن را بشکند، تکه‌ها جابه‌جا می‌شوند و متن را باید از پایین به بالا خواند. با
دادن حداکثر طول خط، `fix()` اول خط‌های بلند را از بین کلمه‌ها می‌شکند:

<div dir="ltr">

```php
$form->setContent(Persian::fix($longText, 40)); // حدود 40 برای فرم مناسب است
```

</div>

رنگ در خط جدید ادامه پیدا می‌کند و متن داخل پرانتز و براکت و دستورهای انگلیسی مثل `/shop buy` نصفه نمی‌شوند.

### نکته‌ها

- **فقط یک بار و آخر کار fix کنید.** اول کل متن را بسازید، بعد `fix()` بزنید. اگر یک کلمه را fix کنید و بعد داخل
  متن دیگری بگذارید و دوباره fix کنید، آن کلمه دو بار برعکس می‌شود.
- **اولین کلمه هر خط سمت راست نمایش داده می‌شود.** برای اینکه دستور سمت چپ و توضیحش سمت راست باشد، اول توضیح را
  بنویسید: `"باز کردن فروشگاه : /shop"`.
- **آرگومان انگلیسی را داخل `<>` نگذارید.** `"/shop <item>"` به شکل `<item> /shop` نمایش داده می‌شود، چون `<...>`
  یک تکه جدا حساب می‌شود. به‌جایش بنویسید `"/shop ItemName"`.
- **به هر بخش رنگ خودش را بدهید.** بخشی که کد رنگ ندارد (مثلا عددی بعد از یک `[تگ]` رنگی) رنگ بخش قبلی را می‌گیرد.
  خط‌ها را با رنگ شروع کنید و هرجا سفید ساده می‌خواهید `§f` بگذارید.
- **از نیم‌فاصله استفاده کنید** (می‌شود، درخواست‌ها). حروف را درست جدا می‌کند و در بازی دیده نمی‌شود.
- **حروف عربی هم پشتیبانی می‌شوند** (أ إ ؤ ئ ة ي ك ى ۀ و لأ لإ لآ). اعراب‌ها (تنوین مثل «قبلاً»، فتحه، کسره، تشدید...)
  در ماینکرفت قابل نمایش نیستند و حذف می‌شوند؛ «هٔ» به «ۀ» تبدیل می‌شود.
- **عدد انگلیسی بنویسید** و به‌جای `٪` کلمه «درصد» بنویسید، چون بعضی فونت‌ها آن را ندارند.
- **اگر فارسی فقط داخل `ModalForm` به شکل مربع نمایش داده می‌شود،** احتمالا ریسورس‌پک شما روی فرم مودال اعمال
  نمی‌شود. برای تایید از `SimpleForm` با دو دکمه استفاده کنید.

### سازنده

ساخته‌شده توسط **Kevin** برای سرور [ApexMine](https://github.com/ApexMine)، با لایسنس [MIT](LICENSE).

</div>
