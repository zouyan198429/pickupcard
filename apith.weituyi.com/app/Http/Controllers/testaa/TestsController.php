<?php

namespace App\Http\Controllers\testaa;

use App\Http\Controllers\Controller;
use App\Models\test\Account;
use App\Models\test\Book;
use App\Models\test\Comment;
use App\Models\test\Countries;
use App\Models\test\Phone;
use App\Models\test\Post;
use App\Models\test\Role;
use App\Models\test\Tag;
use App\Models\test\User;
use App\Models\testVideos;
use Illuminate\Http\Request;

class TestsController extends Controller
{
    /**
     * 测试
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index()
    {
        return 'aaa';
        // 一对一
        // $Phone = User::find(1)->phone;
        // $User = Phone::find(1)->user;

        // 一对多
        // $Comment = Post::find(1)->comments;
        // $Comment = Post::find(1)->comments()->where('nr', '评论cccc')->first();
        // $Post = Comment::find(2)->post;

        // 多对多
        /*
        $User = User::find(2);
        foreach ($User->roles as $role) {
        //foreach ($User->roles()->select('role.id','role.role_name')->get() as $role) {
            echo '<pre>';
            print_r($role->role_name);
            echo '</pre>';
        }
        */
        /*
        $roles = Role::find(2);
        foreach ($roles->users as $user) {
            echo '<pre>';
            // print_r($user->user_name);
            echo $user->pivot->user_id;
            echo '</pre>';
        }
        */
        // 远层的一对多
        /*
        $Country = Countries::find(2);
        foreach ($Country->posts as $post) {
            echo '<pre>';
            print_r($post->title);
            echo '</pre>';
        }
        */
        // 多态关联
        // 要访问一篇文章的所有评论，可以使用动态属性 comments：

//        $post = Post::find(1);
//
//        foreach ($post->comments as $comment) {
//            echo '<pre>';
//            print_r($comment->nr);
//            echo '</pre>';
//        }

//        $Videos = Videos::find(2);
//
//        foreach ($Videos->comments as $comment) {
//            echo '<pre>';
//            print_r($comment->nr);
//            echo '</pre>';
//        }

        // 还可以通过调用 morphTo 方法从多态模型中获取多态关联的所属对象
        /*
        $comment = Comment::find(1);

        $commentable = $comment->commentable;
        echo '<pre>';
        print_r($commentable);
        echo '</pre>';
        */

        // 多对多的多态关联
        // 要访问一篇文章的所有标签，可以使用动态属性 tags：
//        $post = Post::find(1);
//
//        foreach ($post->tags as $tag) {
//            echo '<pre>';
//            print_r($tag->name);
//            echo '</pre>';
//        }

        // 要访问一篇视频的所有标签，可以使用动态属性 tags：
//        $videos = Videos::find(1);
//
//        foreach ($videos->tags as $tag) {
//            echo '<pre>';
//            print_r($tag->name);
//            echo '</pre>';
//        }

        // 访问调用 morphedByMany 的方法名从多态模型中获取多态关联的所属对象

//        $tag = Tag::find(1);
//
//        foreach ($tag->videos as $video) {
//            echo '<pre>';
//            print_r($video->title);
//            echo '</pre>';
//        }

        // 获取所有至少有一条评论的文章...
        // select * from `post` where exists (select * from `comment` where `post`.`id` = `comment`.`commentable_id` and `comment`.`commentable_type` = ?)
        // $posts = post::has('comments')->get();
        // 获取所有至少有三条评论的文章...
        // select * from `post` where (select count(*) from `comment` where `post`.`id` = `comment`.`commentable_id` and `comment`.`commentable_type` = ?) >= 3
//        $posts = Post::has('comments', '>=', 3)->get();
//
//        echo '<pre>';
//        print_r($posts);
//        echo '</pre>';
        // 获取所有至少有一条评论获得投票的文章...
//        $posts = Post::has('comments.votes')->get();
//        echo '<pre>';
//        print_r($posts);
//        echo '</pre>';
        // 获取所有至少有一条评论包含foo字样的文章
        // select * from `post` where exists (select * from `comment` where `post`.`id` = `comment`.`commentable_id` and `comment`.`commentable_type` = ? and `nr` like ?)
//        $posts = Post::whereHas('comments', function ($query) {
//            $query->where('nr', 'like', '评论%');
//        })->get();
//        echo '<pre>';
//        print_r($posts);
//        echo '</pre>';
        // 你想要获取所有没有评论的博客文章，可以传递关联关系名称到 doesntHave 和 orDoesntHave 方法来实现
        // select * from `post` where not exists (select * from `comment` where `post`.`id` = `comment`.`commentable_id` and `comment`.`commentable_type` = ?)
//        $posts = Post::doesntHave('comments')->get();
//        echo '<pre>';
//        print_r($posts);
//        echo '</pre>';
        // select * from `post` where not exists (select * from `comment` where `post`.`id` = `comment`.`commentable_id` and `comment`.`commentable_type` = ? and `nr` like ?)
//        $posts = Post::whereDoesntHave('comments', function ($query) {
//            $query->where('nr', 'like', '评论%');
//        })->get();
//        echo '<pre>';
//        print_r($posts);
//        echo '</pre>';
        // 该方法会放置一个 {relation}_count 字段到结果模型。例如：
        // {"sql":"select `post`.*, (select count(*) from `comment` where `post`.`id` = `comment`.`commentable_id` and `comment`.`commentable_type` = ?) as `comments_count` from `post`","bindings":["posts"],"time":17.01}
//        $posts = Post::withCount('comments')->get();
//
//        foreach ($posts as $post) {
//            echo $post->comments_count;
//        }

        // 获取所有书及其作者：
//        $books = Book::all();
//        foreach ($books as $book) {
//            echo $book->author->name . '<br/>';
//        }
        // 该循环先执行 1 次查询获取表中的所有书，然后另一个查询获取每一本书的作者，因此，如果有25本书，要执行26次查询：1次是获取书本身，剩下的25次查询是为每一本书获取其作者。
// 谢天谢地，我们可以使用渴求式加载来减少该操作到 2 次查询。当查询的时候，可以使用 with 方法指定应该被渴求式加载的关联关系：
//        $books = Book::with('author')->get();
//
//        foreach ($books as $book) {
//            echo $book->author->name . '<br/>';
//        }
        // 在该操作中，只执行两次查询即可：
        // select * from books
        // select * from authors where id in (1, 2, 3, 4, 5, ...)
//        $books = Book::with('author.contacts')->get();
//        foreach ($books as $book) {
//            echo $book->author->contacts->mobile . '<br/>';
//        }
        // 指定更多的查询条件：
//        $users = User::with(['posts' => function ($query) {
//            $query->where('title', 'like', '%1%');
//        }])->get();
//        echo '<pre>';
//        print_r($users);
//        echo '</pre>';
        // 懒惰渴求式加载  在父模型已经被获取后渴求式加载一个关联关系- load 方法
//        $books = book::all();

//        if (true) {
//            $books->load('author');
//        }
//        if (false) {
//            $books->load('author');
//        }
        // 一对多保存
        // save 方法直接插入 Comment 而不是手动设置 Comment 的 post_id 属性
        // 一对多关联时
        // {"sql":"select * from `post` where `post`.`id` = ? limit 1","bindings":[1],"time":15.3}
        // {"sql":"insert into `comment` (`nr`, `post_id`, `updated_at`, `created_at`) values (?, ?, ?, ?)","bindings":["A new comment.",1,"2018-06-22 23:55:44","2018-06-22 23:55:44"],"time":1.13}

        // 多态关联时
        //  {"sql":"select * from `post` where `post`.`id` = ? limit 1","bindings":[1],"time":17.19}
        // {"sql":"insert into `comment` (`nr`, `commentable_type`, `commentable_id`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)","bindings":["A new comment.","posts",1,"2018-06-22 23:59:08","2018-06-22 23:59:08"],"time":1.04}
        /*
        $comment = new Comment(['nr' => 'A new comment.']);
        // $comment = new \App\Models\Comment(['nr' => 'A new comment.']);
        $post = Post::find(1);
        $result = $post->comments()->save($comment);
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        */
        // 保存多个关联模型，可以使用 saveMany 方法：
        // {"sql":"select * from `post` where `post`.`id` = ? limit 1","bindings":[1],"time":35.83}
        // {"sql":"insert into `comment` (`nr`, `commentable_type`, `commentable_id`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)","bindings":["A new comment.","posts",1,"2018-06-23 00:05:03","2018-06-23 00:05:03"],"time":1.07}
        // {"sql":"insert into `comment` (`nr`, `commentable_type`, `commentable_id`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)","bindings":["Another comment.","posts",1,"2018-06-23 00:05:03","2018-06-23 00:05:03"],"time":1.11}
//        $post = Post::find(1);
//
//        $result = $post->comments()->saveMany([
//            new Comment(['nr' => 'A new comment.']),
//            new Comment(['nr' => 'Another comment.']),
//        ]);
//        echo '<pre>';
//        print_r($result);
//        echo '</pre>';
        // create 方法，该方法接收属性数组、创建模型、然后插入数据库。save 和 create 的不同之处在于 save 接收整个 Eloquent 模型实例而 create 接收原生 PHP 数组
//        $post = Post::find(1);
//        $comment = $post->comments()->create([
//            'nr' => 'A new comment.',
//        ]);

        // 使用 createMany 方法来创建多个关联模型：
//        $post = Post::find(1);
//
//        $post->comments()->createMany([
//            [
//                'nr' => 'A new comment.',
//            ],
//            [
//                'nr' => 'Another new comment.',
//            ],
//        ]);
            // 使用 associate 方法，该方法会在子模型设置外键：
//        $user = new User(['country_id'=>1,'user_name' => '测试用户']);
//        $account = new Account(['user_name' => 'zouyantest']);
//
//            //$user = User::find(3);
//            //$account = Account::find(3);
//            //$account->user()->associate($user);
//            //$account->save();
//        $user->save();
//        $result1 = $account->user()->associate($user);
//        $result2 = $account->save();
//        echo '<pre>';
//        print_r($result1);
//        print_r($result2);
//        echo '</pre>';

        // 使用 dissociate 方法。该方法会设置关联关系的外键为 null：
//        $account = Account::find(3);
//        $account->user()->dissociate();
//        $account->save();
        // 多对多关联
        // 附加/分离
        // 通过在连接模型的中间表中插入记录附加角色到用户上，可以使用 attach 方法：
        // {"sql":"insert into `role_user` (`created_at`, `role_id`, `updated_at`, `user_id`) values (?, ?, ?, ?)","bindings":["2018-06-23 01:41:30",3,"2018-06-23 01:41:30",1],"time":28.76}
//        $user = User::find(1);
//        $roleId = 3;
//        $result = $user->roles()->attach($roleId);
//        echo '<pre>';
//        print_r($result);
//        echo '</pre>';

        // 以数组形式传递额外被插入数据到中间表：
        // {"sql":"insert into `role_user` (`created_at`, `notice`, `role_id`, `updated_at`, `user_id`) values (?, ?, ?, ?, ?)","bindings":["2018-06-23 01:44:19","afsadfs",3,"2018-06-23 01:44:19",1],"time":1.17
//        $user = User::find(1);
//        $roleId = 3;
//        $result = $user->roles()->attach($roleId, ['notice' => 'afsadfs']);
//        echo '<pre>';
//        print_r($result);
//        echo '</pre>';

//        $user = User::find(1);
//        $user->roles()->sync([1, 3]);
//        $comment = Comment::find(1);
//        $comment->nr = 'Edit to this comment!1';
//        $comment->save();
//
//        return 'aaa';
//        echo '<pre>';
//        print_r($Comment);
//        echo '</pre>';
//        return 'adfdasfdsaf';
    }
}
