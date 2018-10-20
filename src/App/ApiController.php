<?php
/**
 * Project testSlim.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/20/18
 * Time: 16:05
 */

namespace App;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ApiController
{
    const PRIVATE_TOKEN = 'LKDRGIG2Tb';
    /** @var object \Psr\Container\ContainerInterface */
    protected $container;
    /** @var object DB PDO */
    protected $db;
    /** @var object Log */
    protected $logger;

    /**
     * ApiController constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db        = $this->container->db;
        $this->logger    = $this->container->logger;
    }

    /**
     * Function content
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/20/18 16:34
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function content(Request $request, Response $response)
    {
        $connectIp            = $_SERVER['REMOTE_ADDR'];
        $listConnectIpAllowed = [
            '127.0.0.1'
        ];
        $this->logger->info('~~~~~~~~~~~~~~~~~~~~> Request to API <~~~~~~~~~~~~~~~~~~~~');
        $this->logger->info('IP: ' . $_SERVER['REMOTE_ADDR']);
        $this->logger->info('Request: ' . json_encode($request->getQueryParams()));
        $date           = $request->getQueryParam('date');
        $service        = $request->getQueryParam('service');
        $signature      = $request->getQueryParam('signature');
        $validSignature = hash('sha256', $date . self::PRIVATE_TOKEN . $service);
        if (!in_array($connectIp, $listConnectIpAllowed)) {
            $result = [
                'status' => 5,
                'desc'   => 'Anh ơi, anh không thuộc khu vực em hoạt động, anh vui lòng cung cấp địa bàn cho em, để em cài đặt vào bộ nhớ để sẵn sàng phục vụ anh vào lần sau anh nhé'
            ];
        } else {
            if (empty($date) || empty($service) || empty($signature)) {
                $result = [
                    'status' => 3,
                    'desc'   => 'Anh vui lòng thanh toán trước khi em cung cấp dịch vụ ạ!'
                ];
            } elseif ($signature != $validSignature) {
                $result = [
                    'status' => 4,
                    'desc'   => 'Anh vui lòng nhập mã đúng để kết nối với em. VD: Bạn anh Hưng Đẹp troai',
                    'valid'  => $validSignature
                ];
            } else {

                // Call dữ liệu trong DB
                /** @var object $selectStatement */
                $selectStatement = $this->db->select()
                                            ->from('KetQuaXoSo')
                                            ->where('date', '=', $date)
                                            ->where('service', '=', strtoupper($service));
                /** @var object $execute */
                $execute = $selectStatement->execute();
                /** @var object $data */
                $data = $execute->fetch();
                if (empty($data)) {
                    $result = [
                        'status'      => 1,
                        'description' => 'Không tồn tại dữ liệu trong hệ thống!'
                    ];
                } else {
                    $result = [
                        'status'      => 0,
                        'description' => 'Tìm thấy dữ liệu phù hợp với yêu cầu',
                        'data'        => $data
                    ];
                }
            }
        }
        $this->logger->info('Response: ' . json_encode($result));

        return $response->withJson($result);
    }
}
