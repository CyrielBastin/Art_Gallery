<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\NewsletterMessages;
use App\Entity\Painting;
use App\Entity\PaintingComment;
use App\Entity\PaintingMedia;
use App\Entity\PaintingStyle;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\ArtistEditType;
use App\Form\ArtistType;
use App\Form\NewsletterMessageType;
use App\Form\PaintingCommentType;
use App\Form\PaintingEditType;
use App\Form\PaintingMediaEditType;
use App\Form\PaintingMediaType;
use App\Form\PaintingStyleEditType;
use App\Form\PaintingStyleType;
use App\Form\PaintingType;
use App\Form\UserDashboardType;
use App\Service\NewsletterService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin", name="admin_", requirements={"_locale"="%app.locales%"})
 */
class AdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function viewDashboard()
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /******************************************************************************************************************
     * Paintings
     *****************************************************************************************************************/

    /**
     * @Route("/paintings/list", name="paintings_list")
     */
    public function showListPaintings(PaginatorInterface $paginator, Request $request)
    {
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->adminListPainting();

        $pagination = $paginator->paginate($paintings, $request->query->getInt('page', 1), 12);

        return $this->render('admin/painting/painting_list.html.twig', ['paintings' => $pagination]);
    }

    /**
     * @Route("/paintings/add-one/add-from-dashboard", name="painting_add")
     */
    public function addPaintingDashboard(Request $request)
    {
        $painting = new Painting();
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($painting);
            $this->em->flush();

            $translated = $this->translator->trans('The artwork %painting% has been correctly added', ['%painting%' => $painting->getTitle()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_paintings_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/painting/painting_add_one.html.twig', ['painting' => $painting, 'form' => $form->createView()]);
    }

    /**
     * @Route("/paintings/edit-one/{id}/edit-from-dashboard", name="painting_edit")
     */
    public function editPaintingDashboard(Request $request, Painting $painting)
    {
        $form = $this->createForm(PaintingEditType::class, $painting);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($painting);
            $this->em->flush();

            $translated = $this->translator->trans('The artwork %painting% has been correctly modified', ['%painting%' => $painting->getTitle()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_paintings_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/painting/painting_edit_one.html.twig', ['painting' => $painting, 'form' => $form->createView()]);
    }

    /**
     * @Route("/paintings/delete-one/{id}/delete-from-dashboard", name="painting_delete")
     */
    public function deletePaintingDashboard(Request $request, $id)
    {
        $painting = $this->em->getRepository(Painting::class)->find($id);
        $this->em->remove($painting);
        $this->em->flush();

        $translated = $this->translator->trans('The artwork %painting% has been properly deleted', ['%painting%' => $painting->getTitle()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('admin_paintings_list', ['_locale' => $request->getLocale()]);
    }

    /******************************************************************************************************************
     * Medias
     *****************************************************************************************************************/

    /**
     * @Route("/medias/list", name="medias_list")
     */
    public function showListMedias()
    {
        $medias = $this->getDoctrine()->getRepository(PaintingMedia::class)->adminListMedia();

        return $this->render('admin/media/media_list.html.twig', ['medias' => $medias]);
    }

    /**
     * @Route("/medias/add-one/add-from-dashboard", name="media_add")
     */
    public function addMediaDashboard(Request $request)
    {
        $media = new PaintingMedia();
        $form = $this->createForm(PaintingMediaType::class, $media);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($media);
            $this->em->flush();

            $translated = $this->translator->trans('Media %media% successfully added', ['%media%' => $media->getName()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_medias_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/media/media_add_one.html.twig', ['media' => $media, 'form' => $form->createView()]);
    }

    /**
     * @Route("/medias/edit-one/{id}/edit-from-dashboard", name="media_edit")
     */
    public function editMediaDashboard(Request $request, PaintingMedia $media)
    {
        $form = $this->createForm(PaintingMediaEditType::class, $media);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($media);
            $this->em->flush();

            $translated = $this->translator->trans('Media %media% successfully modified', ['%media%' => $media->getName()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_medias_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/media/media_edit_one.html.twig', ['media' => $media, 'form' => $form->createView()]);
    }

    /**
     * @Route("/medias/delete-one/{id}/delete-from-dashboard", name="media_delete")
     */
    public function deleteMediaDashboard(Request $request, $id)
    {
        $media = $this->em->getRepository(PaintingMedia::class)->find($id);
        $this->em->remove($media);
        $this->em->flush();

        $translated = $this->translator->trans('Media %media% successfully removed', ['%media%' => $media->getName()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('admin_medias_list', ['_locale' => $request->getLocale()]);
    }

    /******************************************************************************************************************
     * Styles
     *****************************************************************************************************************/

    /**
     * @Route("/styles/list", name="styles_list")
     */
    public function showListStyles()
    {
        $styles = $this->getDoctrine()->getRepository(PaintingStyle::class)->adminListStyle();

        return $this->render('admin/style/style_list.html.twig', ['styles' => $styles]);
    }

    /**
     * @Route("/styles/add-one/add-from-dashboard", name="style_add")
     */
    public function addStyleDashboard(Request $request)
    {
        $style = new PaintingStyle();
        $form = $this->createForm(PaintingStyleType::class, $style);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($style);
            $this->em->flush();

            $translated = $this->translator->trans('Style %style% successfully added', ['%style%' => $style->getName()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_styles_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/style/style_add_one.html.twig', ['style' => $style, 'form' => $form->createView()]);
    }

    /**
     * @Route("/styles/edit-one/{id}/edit-from-dashboard", name="style_edit")
     */
    public function editStyleDashboard(Request $request, PaintingStyle $style)
    {
        $form = $this->createForm(PaintingStyleEditType::class, $style);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($style);
            $this->em->flush();

            $translated = $this->translator->trans('Style %style% successfully modified', ['%style%' => $style->getName()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_styles_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/style/style_edit_one.html.twig', ['style' => $style, 'form' => $form->createView()]);
    }

    /**
     * @Route("/styles/delete-one/{id}/delete-from-dashboard", name="style_delete")
     */
    public function deleteStyleDashboard(Request $request, $id)
    {
        $style = $this->em->getRepository(PaintingStyle::class)->find($id);
        $this->em->remove($style);
        $this->em->flush();

        $translated = $this->translator->trans('Style %style% successfully removed', ['%style%' => $style->getName()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('admin_styles_list', ['_locale' => $request->getLocale()]);
    }

    /******************************************************************************************************************
     * Artists
     *****************************************************************************************************************/

    /**
     * @Route("/artists/list", name="artists_list")
     */
    public function showListArtists(Request $request, PaginatorInterface $paginator)
    {
        $artists = $this->getDoctrine()->getRepository(Artist::class)->adminListArtist();

        $pagination = $paginator->paginate($artists, $request->query->getInt('page', 1), 12);

        return $this->render('admin/artist/artist_list.html.twig', ['artists' => $pagination]);
    }

    /**
     * @Route("/artists/add-one/add-from-dashboard", name="artist_add")
     */
    public function addArtistDashboard(Request $request)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($artist);
            $this->em->flush();

            $translated = $this->translator->trans('Artist %artist% successfully added', ['%artist%' => $artist->getArtist()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_artists_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/artist/artist_add_one.html.twig', ['artist' => $artist, 'form' => $form->createView()]);
    }

    /**
     * @Route("/artists/edit-one/{id}/edit-from-dashboard", name="artist_edit")
     */
    public function editArtistDashboard(Request $request, Artist $artist)
    {
        $form = $this->createForm(ArtistEditType::class, $artist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($artist);
            $this->em->flush();

            $translated = $this->translator->trans('Artist %artist% successfully modified', ['%artist%' => $artist->getArtist()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_artists_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/artist/artist_edit_one.html.twig', ['artist' => $artist, 'form' => $form->createView()]);
    }

    /**
     * @Route("/artists/delete-one/{id}/delete-from-dashboard", name="artist_delete")
     */
    public function deleteArtistDashboard(Request $request, $id)
    {
        $artist = $this->em->getRepository(Artist::class)->find($id);
        $this->em->remove($artist);
        $this->em->flush();

        $translated = $this->translator->trans('Artist %artist% successfully removed', ['%artist%' => $artist->getArtist()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('admin_artists_list', ['_locale' => $request->getLocale()]);
    }

    /******************************************************************************************************************
     * Newsletter
     *****************************************************************************************************************/

    /**
     * @Route("/newsletter/view-all", name="newsletter_view_all")
     */
    public function adminViewAllNewsletter(PaginatorInterface $paginator, Request $request)
    {
        $newsletter_messages = $this->getDoctrine()->getRepository(NewsletterMessages::class)->findAll();

        $pagination = $paginator->paginate($newsletter_messages, $request->query->getInt('page', 1), 6);

        return $this->render('admin/newsletter/newsletter_list.html.twig', ['newsletters' => $pagination]);
    }

    /**
     * @Route("/newsletter/create-one", name="newsletter_create_one")
     */
    public function createNewsletter(Request $request, NewsletterService $service)
    {
        $newsletter = new NewsletterMessages();
        $form = $this->createForm(NewsletterMessageType::class, $newsletter);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newsletter->setCreatedAt(new \DateTime('now'));
            $this->em->persist($newsletter);
            $this->em->flush();

            $service->sendNewsletter($newsletter);
            return $this->redirectToRoute('redirect_from_admin_newsletter_create_one');
        }

        return $this->render('admin/newsletter/newsletter_create_one.html.twig', ['form' => $form->createView()]);
    }

    /******************************************************************************************************************
     * Users
     *****************************************************************************************************************/

    /**
     * @Route("/users/list", name="users_list")
     */
    public function showListUsers(PaginatorInterface $paginator, Request $request)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->adminListUser();

        $pagination = $paginator->paginate($users, $request->query->getInt('page', 1), 15);

        return $this->render('admin/user/user_list.html.twig', ['users' => $pagination]);
    }

    /**
     * @Route("/users/edit-one/{pseudo}/edit-from-dashboard", name="user_edit")
     */
    public function editUserDashboard(Request $request, UserProfile $userProfile)
    {
        $form = $this->createForm(UserDashboardType::class, $userProfile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $form_values = $request->request->all();
            $userProfile->getUser()->setEmail($form_values['user_dashboard']['email']);
            $this->em->getRepository(User::class)->adminSetRole($userProfile->getUser()->getId(), $form_values['user_dashboard']['role']);

            $this->em->persist($userProfile);
            $this->em->flush();

            $translated = $this->translator->trans('The user profile %user% has been updated', ['%user%' => $userProfile->getPseudo()]);
            $this->addFlash('info', $translated);

            return $this->redirectToRoute('admin_users_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/user/user_edit_one.html.twig', ['user' => $userProfile, 'form' => $form->createView()]);
    }

    /**
     * @Route("/users/delete-one/{id}/delete-from-dashboard", name="user_delete")
     */
    public function deleteUserDashboard(Request $request, $id)
    {
        $user = $this->em->getRepository(User::class)->find($id);
        $this->em->remove($user);
        $this->em->flush();

        $translated = $this->translator->trans('The user profile %user% has been properly deleted', ['%user%' => $user->getUserProfile()->getPseudo()]);
        $this->addFlash('info', $translated);

        return $this->redirectToRoute('admin_users_list', ['_locale' => $request->getLocale()]);
    }

    /******************************************************************************************************************
     * Comments
     *****************************************************************************************************************/

    /**
     * @Route("/comments/overview", name="comments_overview")
     */
    public function showCommentsOverview(PaginatorInterface $paginator, Request $request)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->adminCommentsUserOverview();
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->adminCommentsPaintingOverview();

        $pagination_user = $paginator->paginate($users, $request->query->getInt('page', 1), 15);
        $pagination_painting = $paginator->paginate($paintings, $request->query->getInt('page', 1), 15);

        return $this->render('admin/comment/comment_user_overview.html.twig', ['users' => $pagination_user, 'paintings' => $pagination_painting]);
    }

    /**
     * @Route("/comments/by-users/{user_id}/view/comments", name="comments_by_user")
     */
    public function showCommentsByUser($user_id, PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->adminCommentByUser($user_id);
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->adminPaintingCommentByUser($user_id);

        $pagination = $paginator->paginate($paintings, $request->query->getInt('page', 1), 8);

        return $this->render('admin/comment/comment_user_user_detail.html.twig', ['user' => $user, 'paintings' => $pagination]);
    }

    public function generateUserCommentsByPainting($user_id, $painting_id)
    {
        $comments = $this->getDoctrine()->getRepository(Painting::class)->adminCommentsUserByPaintingId($user_id, $painting_id);

        return $this->render('admin/comment/comment_by_user_per_painting.html.twig', ['comments' => $comments]);
    }

    /**
     * @Route("/comments/edit-one/{id}/with-user-{user_id}/edit-from-dashboard", name="comment_edit")
     */
    public function editCommentUserDashboard(PaintingComment $paintingComment, Request $request, $user_id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $user_id]);
        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($paintingComment);
            $this->em->flush();

            return $this->redirectToRoute('redirect_from_admin_comment_edit', ['user_id' => $user_id]);
        }

        return $this->render('admin/comment/comment_user_edit_one.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/comments/delete-one/{commentary_id}/delete-{user_id}-from-dashboard", name="comment_delete")
     */
    public function deleteCommentUserDashboard($commentary_id, $user_id)
    {
        $commentary = $this->getDoctrine()->getRepository(PaintingComment::class)->find($commentary_id);
        $this->em->remove($commentary);
        $this->em->flush();

        return $this->redirectToRoute('redirect_from_admin_comment_delete', ['user_id' => $user_id]);
    }

    /**
     * @Route("/comments/by-paintings/{painting_id}/view/comments", name="comments_by_paintings")
     */
    public function showCommentsByPaintings($painting_id, Request $request, PaginatorInterface $paginator)
    {
        $painting = $this->getDoctrine()->getRepository(Painting::class)->findById($painting_id);
        $painting_commentary = $this->getDoctrine()->getRepository(PaintingComment::class)->findByPaintingId($painting_id);
        $pagination = $paginator->paginate($painting_commentary, $request->query->getInt('page', 1), 7);

        $paintingComment = new PaintingComment();
        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $paintingComment->setUser($this->getUser());
            $paintingComment->setPainting($this->getDoctrine()->getRepository(Painting::class)->findOneBy(['id' => $painting_id]));
            $paintingComment->setPostedAt(new \DateTime('now'));
            //
            $this->em->persist($paintingComment);
            $this->em->flush();

            return $this->redirectToRoute('redirect_from_admin_comment_by_painting', ['painting_id' => $painting_id]);
        }

        return $this->render('admin/comment/comment_user_painting_detail.html.twig', ['painting' => $painting, 'commentaries' => $pagination, 'form' => $form->createView()]);
    }

    /**
     * @Route("/comments/by-paintings/edit-{id}/comment-from/{painting_id}/{user_id}/user", name="comments_by_paintings_edit")
     */
    public function editAdminCommentByPainting(Request $request, PaintingComment $paintingComment, $painting_id, $user_id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $user_id]);
        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($paintingComment);
            $this->em->flush();

            return $this->redirectToRoute('admin_comments_by_paintings', ['_locale' => $request->getLocale(), 'painting_id' => $painting_id]);
        }

        return $this->render('admin/comment/comment_user_edit_one.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/comments/by-paintings/delete-{painting_comment_id}/on-painting-{painting_id}/delete-from-dashboard", name="comments_by_paintings_delete")
     */
    public function deleteAdminCommentByPainting(Request $request, $painting_comment_id, $painting_id)
    {
        $commentary = $this->getDoctrine()->getRepository(PaintingComment::class)->find($painting_comment_id);
        $this->em->remove($commentary);
        $this->em->flush();

        return $this->redirectToRoute('admin_comments_by_paintings', ['_locale' => $request->getLocale(), 'painting_id' => $painting_id]);
    }
}
